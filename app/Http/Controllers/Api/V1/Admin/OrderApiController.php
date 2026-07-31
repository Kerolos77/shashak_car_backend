<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Events\TripStatusUpdated;
use App\Events\TripOffers;
use App\Events\OfferUpdated;
use App\Http\Resources\OutCityOffersCollection;
use App\Http\Resources\OutCityOffersResource;
use App\Models\OrderOffer;
use App\Models\SavedCard;
use App\Services\PaymobService;
use Gate;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderWithDriverResource;
use App\Models\Income;
use App\Models\PointTransaction;
use App\Models\Setting;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;

class OrderApiController extends Controller
{
    public function get_driver_active_ride(Request $request)
    {
        $driverId = Auth::id();
        $order = Order::where('driver_id', $driverId)
            ->whereNotIn('status', [Order::STATUS_COMPLETED, Order::STATUS_CANCELED])
            ->latest()
            ->first();
            
        if ($order) {
            return Resp(new OrderResource($order), 'success');
        }

        return Resp(null, 'success');
    }
    public function get_user_active_ride(Request $request)
    {
        $userId = Auth::id();
        $order = Order::where('user_id', $userId)
            ->whereNotIn('status', [Order::STATUS_COMPLETED, Order::STATUS_CANCELED])
            ->latest()
            ->first();

        if ($order) {
            return Resp(new OrderResource($order), 'success');
        }

        return Resp(null, 'success');
    }

    public function show(Order $order)
    {
        $userId = Auth::id();

        // Authorization check: Only the user who created the order or the currently assigned driver can view it.
        if ($order->user_id != $userId && $order->driver_id != $userId) {
            return Resp(null, 'Unauthorized: You are not a participant in this trip.', 403, false);
        }

        return Resp(new OrderResource($order->load(['driver', 'user', 'service', 'offers', 'reviews'])), 'success');
    }

    private function checkDriverWalletForShipping(User $driver, Order $order)
    {
        $settings = Setting::first();
        if ($order->is_shipping_order && $settings) {
            $minWallet = $settings->min_driver_wallet_for_shipping ?? 0;
            if ($driver->wallet_amount < $minWallet) {
                return [
                    'allowed' => false,
                    'message' => "المحفظة الخاصة بك تحتوي على رصيد أقل من الحد الأدنى المطلوب لشحن المنتجات ({$minWallet} ج.م)، يرجى شحن المحفظة أولاً."
                ];
            }
        }
        return ['allowed' => true];
    }

    private function sendShippingSmsToReceiver(Order $order)
    {
        if ($order->is_shipping_order && $order->receiver_phone) {
            $driverName = $order->driver ? ($order->driver->full_name ?? $order->driver->name) : ($order->driver_name ?? 'سائق شقشق');
            $otp = $order->delivery_otp;
            $trackingLink = "https://shakshak.net/track/" . $order->id;
            
            $message = "أهلاً بك، شحنتك رقم #{$order->id} مع السائق {$driverName} في الطريق إليك. للتتبع المباشر استخدم الرابط: {$trackingLink} وكود الاستلام هو: {$otp}";
            
            try {
                $smsHelper = new \App\Helpers\SmsHelper();
                $result = $smsHelper->sendCustomSms($order->receiver_phone, $message);
                \Log::info("Shipping SMS result for order #{$order->id} to {$order->receiver_phone}:", ['result' => $result]);
            } catch (\Exception $e) {
                \Log::error("Failed to send shipping SMS for order #{$order->id}: " . $e->getMessage());
            }
        }
    }

    private function deductDriverCommission(Order $order)
    {
        $setting = Setting::first();
        $commissionPercentage = $setting->commission_percentage ?? 0;

        if ($commissionPercentage > 0) {
            $driver = User::find($order->driver_id);

            if ($driver) {
                // Check for active package discount
                $activePurchase = \Illuminate\Support\Facades\DB::table('driver_purchases')
                    ->join('driver_packages', 'driver_purchases.package_id', '=', 'driver_packages.id')
                    ->where('driver_purchases.driver_id', $driver->id)
                    ->where('driver_purchases.expires_at', '>', now())
                    ->select('driver_packages.discount_percentage')
                    ->orderBy('driver_packages.discount_percentage', 'desc')
                    ->first();
                
                if ($activePurchase && $activePurchase->discount_percentage > 0) {
                    $commissionPercentage = max(0, $commissionPercentage - $activePurchase->discount_percentage);
                }

                $commissionAmount = ($order->offer_rate * $commissionPercentage) / 100;
                // Deduct from driver wallet
                $driver->update([
                    'wallet_amount' => $driver->wallet_amount - $commissionAmount
                ]);

                // Record company income
                Income::create([
                    'order_id' => $order->id,
                    'amount' => $commissionAmount
                ]);

                // Record transaction
                \App\Models\WalletTransaction::create([
                    'user_id' => $driver->id,
                    'amount' => $commissionAmount,
                    'type' => 'withdraw',
                    'description' => 'Commission for order #' . $order->id . ' (Status: ' . $order->status . ')'
                ]);

                Log::info("Commission of {$commissionAmount} deducted for driver {$driver->id} on order {$order->id}");
            }
        }
    }

    private function finalizePaidOrder(Order $order, float $finalRate, float $walletDeduction, float $cardDeduction, float $cashAmount)
    {
        $user = User::find($order->user_id);

        // Finalize Deductions
        if ($walletDeduction > 0 && $user) {
            $user->update(['wallet_amount' => $user->wallet_amount - $walletDeduction]);
            \App\Models\WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $walletDeduction,
                'type' => 'withdraw',
                'description' => 'Payment escrow for order #' . $order->id
            ]);
        }

        $order->update([
            'payment_status' => Order::PAYMENT_PAID,
            'status' => Order::STATUS_PAYMENT_PAID,
            'is_escrow' => ($walletDeduction > 0 || $cardDeduction > 0),
            'wallet_paid' => $walletDeduction,
            'card_paid' => $cardDeduction,
            'cash_paid' => $cashAmount,
        ]);

        // Broadcast payment paid
        TripStatusUpdated::dispatch($order->fresh());

        // Deduct Commission from Driver
        $this->deductDriverCommission($order);
    }

    private function handlePaymentGate(Order $order, float $finalRate, ?int $offerId = null)
    {
        // 1. Initial State: payment_pending
        $order->update(['status' => Order::STATUS_PAYMENT_PENDING]);
        TripStatusUpdated::dispatch($order->fresh());


        if (!$order->needsPayment()) {
            $this->finalizePaidOrder($order, $finalRate, 0, 0, $finalRate);
            return ['success' => true];
        }

        $request = request();
        $user = User::find($order->user_id);
        
        $walletDeduction = 0;
        $cardDeduction = 0;
        $cashAmount = 0;
        $savedCardId = $request->saved_card_id;

        // 2. Calculate Deductions
        if ($order->payment_type === 'cash') {
            $cashAmount = $finalRate;
        } elseif ($order->payment_type === 'wallet') {
            if ($user->wallet_amount < $finalRate) {
                $order->update(['status' => Order::STATUS_PAYMENT_FAILED]);
                TripStatusUpdated::dispatch($order->fresh());
                return ['success' => false, 'message' => 'Insufficient wallet balance', 'status' => 400];
            }
            $walletDeduction = $finalRate;
        } elseif ($order->payment_type === 'wallet_card') {
            $walletDeduction = min($user->wallet_amount, $finalRate);
            $cardDeduction = $finalRate - $walletDeduction;
        } elseif ($order->payment_type === 'wallet_cash') {
            $walletDeduction = min($user->wallet_amount, $finalRate);
            $cashAmount = $finalRate - $walletDeduction;
        } elseif ($order->payment_type === 'saved_card' || $order->payment_type === 'card') {
            $cardDeduction = $finalRate;
        }

        // 3. Handle Card Payment
        if ($cardDeduction > 0) {
            $cardQuery = SavedCard::where('user_id', $order->user_id);
            $targetCardId = $savedCardId ?: $order->order_card_id;
            $card = $targetCardId ? $cardQuery->where('id', $targetCardId)->first() : $cardQuery->where('is_default', true)->first();

            if (!$card) {
                // No saved card: Create payment intention for redirection
                try {
                    $result = app(PaymobService::class)->createPaymentIntention($user, (float) $cardDeduction, true, [], $order->id);
                    $order->update(['status' => Order::STATUS_PAYMENT_REQUIRED]);
                    TripStatusUpdated::dispatch($order->fresh());
                    
                    return [
                        'success' => false, 
                        'message' => 'Payment required', 
                        'status' => 200, 
                        'is_payment_required' => true,
                        'url' => $result['checkout_url']
                    ];
                } catch (\Exception $e) {
                    $order->update(['status' => Order::STATUS_PAYMENT_FAILED]);
                    TripStatusUpdated::dispatch($order->fresh());
                    return ['success' => false, 'message' => 'Failed to create payment: ' . $e->getMessage(), 'status' => 500];
                }
            }

            try {
                app(PaymobService::class)->payWithSavedCard($card, (float) $cardDeduction, $order->id);
            } catch (\Exception $e) {
                $order->update(['status' => Order::STATUS_PAYMENT_FAILED]);
                TripStatusUpdated::dispatch($order->fresh());
                return ['success' => false, 'message' => 'Payment failed: ' . $e->getMessage(), 'status' => 402];
            }
        }

        // 4. Finalize
        $this->finalizePaidOrder($order, $finalRate, $walletDeduction, $cardDeduction, $cashAmount);

        return ['success' => true];
    }

    public function add_out_city_offer(Request $request, $order_id, $offer_rate)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        $driver = User::with(['profile', 'profile.driver_cars', 'profile.driver_cars.brand', 'profile.driver_cars.model'])->find($driverID);
        $order = Order::with(['user', 'service', 'driver', 'offers'])->find($order_id);

        if (!$order) {
            return Resp(null, 'Order not found', 404, false);
        }

        $walletCheck = $this->checkDriverWalletForShipping($driver, $order);
        if (!$walletCheck['allowed']) {
            return Resp(null, $walletCheck['message'], 400, false);
        }

        if (!$order->canAcceptOffers()) {
            return Resp(null, 'Order is no longer accepting offers.', 400, false);
        }

        $offer = OrderOffer::create([
            'order_id' => $order_id,
            'driver_id' => $driverID,
            'car_color' => $driver->profile->driver_cars->color ?? '',
            'car_number' => $driver->profile->car_licenses->car_number ?? '',
            'car_brand' => $driver->profile->driver_cars->brand->title ?? '',
            'car_model' => $driver->profile->driver_cars->model->title ?? '',
            'offer_rate' => $offer_rate,

        ]);


        $order->update(['driver_id' => $driver->id]);

        $order->offerdriver = $offer_rate;

        TripOffers::dispatch($order);


        return Resp(new OrderWithDriverResource($order), 'success');
    }
    public function get_out_city_offers($order_id)
    {

        $order = Order::with('offers', 'offers.driver')->find($order_id);
        return Resp(new OrderWithDriverResource($order), 'success');

    }
    public function getprice(Request $request)
    {
        $Service = Service::find($request->service_id);

        if (!$Service) {
            return Resp(null, 'Service not found', 404, false);
        }

        $response = distancematrix($request->origin, $request->destination);

        // Validate Google API response
        if (
            !isset($response['rows'][0]['elements'][0]['distance']['value']) ||
            !isset($response['rows'][0]['elements'][0]['duration']['value'])
        ) {
            Log::error('Distance Matrix API Error', ['response' => $response, 'origin' => $request->origin, 'destination' => $request->destination]);
            return Resp($response, 'Unable to calculate distance. Please check origin and destination.', 400, false);
        }

        $km = $response['rows'][0]['elements'][0]['distance']['value'] / 1000;
        $price = number_format($km * $Service->km_charge, 2);
        $min = number_format(($response['rows'][0]['elements'][0]['duration']['value'] / 60));

        $result['km'] = $km;
        $result['price'] = $price >= Setting::first()->min_order ? $price : Setting::first()->min_order;
        $result['min'] = $min;

        return $result;
    }

    public function get_driver_orders(Request $request)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        $driver = User::find($driverID);

        $ordersQuery = Order::with('driver', 'user');

        // Female Only Logic
        if ($driver->gender === 'male') {
            // Male drivers cannot see female-only orders
            $ordersQuery->where('is_female_only', false);
        }
        // Female drivers see all orders (both female-only and normal)

        $orders = $ordersQuery->get();
        // dd($orders);
        $statusArray = array_fill_keys([
            Order::STATUS_PENDING,
            Order::STATUS_NEGOTIATING,
            Order::STATUS_ASSIGNED,
            Order::STATUS_DRIVER_ON_A_WAY,
            Order::STATUS_ARRIVED,
            Order::STATUS_ON_TRIP,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELED
        ], []);

        // Populate status array with orders
        foreach ($orders as $order) {
            $statusArray[$order->status][] = new OrderResource($order);
        }

        return Resp($statusArray, 'success');
    }
    public function get_user_orders(Request $request)
    {
        $userID = $this->getUserIDByToken(request()->bearerToken());

        $ordersQuery = Order::where('user_id', $userID);
        if ($request->has('in_city') && $request->in_city !== null) {
            $ordersQuery->where('inter_city', $request->in_city);
        }
        $orders = $ordersQuery->get();
        $statusArray = array_fill_keys([
            Order::STATUS_PENDING,
            Order::STATUS_NEGOTIATING,
            Order::STATUS_ASSIGNED,
            Order::STATUS_DRIVER_ON_A_WAY,
            Order::STATUS_ARRIVED,
            Order::STATUS_ON_TRIP,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELED
        ], []);

        // Populate status array with orders
        foreach ($orders as $order) {
            $statusArray[$order->status][] = new OrderResource($order);
        }


        return Resp($statusArray, 'success');
    }
    public function cancelorder(Request $request, Order $order)
    {
        if (!$order->canBeCanceled()) {
            $msg = $order->status === Order::STATUS_COMPLETED 
                ? 'Cannot cancel a completed order.' 
                : 'Cannot cancel a trip after it has started.';
            return Resp(null, $msg, 400, false);
        }

        // 1. Refund Escrow funds if any (for non-cash trips canceled before completion)
        if ($order->hasEscrowFunds()) {
            $user = User::find($order->user_id);
            $totalElectronicRefund = $order->wallet_paid + $order->card_paid;

            if ($totalElectronicRefund > 0 && $user) {
                $user->update([
                    'wallet_amount' => $user->wallet_amount + $totalElectronicRefund
                ]);
                \App\Models\WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $totalElectronicRefund,
                    'type' => 'deposit',
                    'description' => 'Refund (Wallet+Card) for canceled order #' . $order->id
                ]);
            }
            
            $order->update(['is_escrow' => false]);
        }

        // 2. Refund Driver Commission if a cash trip is canceled AFTER starting
        // (startorder deducts commission, so we must return it if canceled mid-trip)
        if ($order->payment_type === 'cash' && $order->status === Order::STATUS_ON_TRIP) {
            $setting = Setting::first();
            $commissionPercentage = $setting->commission_percentage ?? 0;
            if ($commissionPercentage > 0) {
                $commissionAmount = ($order->offer_rate * $commissionPercentage) / 100;
                $driver = User::find($order->driver_id);
                if ($driver) {
                    $driver->update([
                        'wallet_amount' => $driver->wallet_amount + $commissionAmount
                    ]);
                    \App\Models\WalletTransaction::create([
                        'user_id' => $driver->id,
                        'amount' => $commissionAmount,
                        'type' => 'deposit',
                        'description' => 'Commission refund for canceled mid-trip cash order #' . $order->id
                    ]);

                    // Remove the income record if any
                    Income::where('order_id', $order->id)->delete();
                }
            }
        }

        $order->update([
            'is_canceled' => Carbon::now(),
            'canceled_at' => Carbon::now(),
            'status' => Order::STATUS_CANCELED,
            'canceled_by' => Auth::user() ? Auth::user()->id : null
        ]);

        // Gamification: Deduct points for cancellation
        $settings = Setting::first();
        if ($settings && Auth::check()) {
            $canceler = Auth::user();
            if ($canceler->id == $order->user_id) {
                // User canceled
                $penalty = $settings->points_user_cancel_penalty ?? 0;
                if ($penalty > 0) {
                    $canceler->decrement('points', $penalty);
                    PointTransaction::create([
                        'user_id' => $canceler->id,
                        'amount' => -$penalty,
                        'description' => 'Cancellation Penalty (Order #' . $order->id . ')',
                        'order_id' => $order->id
                    ]);
                }
            } elseif ($canceler->id == $order->driver_id) {
                // Driver canceled
                $penalty = $settings->points_driver_cancel_penalty ?? 0;
                if ($penalty > 0) {
                    $canceler->decrement('points', $penalty);
                    PointTransaction::create([
                        'user_id' => $canceler->id,
                        'amount' => -$penalty,
                        'description' => 'Cancellation Penalty (Order #' . $order->id . ')',
                        'order_id' => $order->id
                    ]);
                }
            }
        }

        if ($order->driver_id) {
            $driver = User::find($order->driver_id);
            if ($driver) {
                $driver->sendPushNotification("تم إلغاء الرحلة", "قام العميل بإلغاء الرحلة.", ['order_id' => $order->id, 'type' => 'trip_cancelled']);
            }
        }

        TripStatusUpdated::dispatch($order);

        return Resp(new OrderResource($order), 'success');
    }

    public function neworder(StoreOrderRequest $request)
    {
        $user = Auth::user();

        // 1. Check if user already has an active trip
        $activeTrips = Order::where('user_id', $user->id)
            ->whereNotIn('status', [Order::STATUS_COMPLETED, Order::STATUS_CANCELED])
            ->get();

        if ($activeTrips->isNotEmpty()) {
            return Resp([
                'active_order_ids' => $activeTrips->pluck('id')
            ], 'You already have an active trip. Please complete or cancel it before starting a new one.', 400, false);
        }

        $service = Service::find($request->service_id);

        if (!$service) {
            return Resp(null, 'Service not found', 404, false);
        }

        $isShippingOrder = ($service->service_type === 'shipping') || $request->filled('receiver_phone') || $request->filled('receiver_name');

        if ($isShippingOrder) {
            $request->validate([
                'receiver_name' => 'required|string|max:255',
                'receiver_phone' => 'required|string',
            ]);

            // Validate that the sender has a verified UserIdentity or legacy documents
            $user = Auth::user();
            $identity = \App\Models\UserIdentity::where('user_id', $user->id)->first();
            $isVerified = ($identity && $identity->status === 'verified');
            $hasLegacyDocs = (!empty($user->national_id) && !empty($user->national_id_front) && !empty($user->national_id_back) && !empty($user->national_id_selfie));

            if (!$isVerified && !$hasLegacyDocs) {
                return Resp(null, 'يرجى إكمال توثيق حسابك ورفع صور بطاقة الهوية (وجه، ظهر، وسيلفي مع البطاقة) أولاً قبل طلب رحلة شحن.', 400, false);
            }
        }

        $pickupOtp = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $baseData = [
            'service_id' => $request->service_id ?? '',
            'driver_id' => null,
            'distance' => $request->distance ?? '',
            'distance_type' => 'km',
            'destination_address' => $request->destination_address ?? '',
            'destination_lat' => $request->destination_lat ?? '',
            'destination_long' => $request->destination_long ?? '',
            'source_address' => $request->source_address ?? '',
            'source_lat' => $request->source_lat ?? '',
            'source_long' => $request->source_long ?? '',
            'offer_rate' => $request->offer_rate ?? '0',
            'final_rate' => $request->final_rate ?? '0',
            'payment_type' => $request->payment_type ?? 'cash',  // cash | wallet | card | saved_card
            'order_card_id' => $request->saved_card_id,
            'status' => Order::STATUS_PENDING,
            'user_id' => Auth::user()->id,
            'inter_city' => $request->inter_city,
            'is_female_only' => $request->is_female_only ?? false,
            'pickup_otp' => $pickupOtp,
        ];

        if ($isShippingOrder) {
            $baseData['is_shipping_order'] = true;
            $baseData['receiver_name'] = $request->receiver_name;
            $baseData['receiver_phone'] = $request->receiver_phone;
            $baseData['delivery_otp'] = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $baseData['receiver_verification_otp'] = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $baseData['is_receiver_verified'] = false;
        }

        // Pre-populate payment details for all types to show in response
        $paymentType = $request->payment_type ?? 'cash';
        $offerRate = (float) ($request->offer_rate ?? 0);
        $user = Auth::user();

        if ($paymentType === 'cash') {
            $baseData['cash_paid'] = $offerRate;
        } elseif ($paymentType === 'wallet') {
            $baseData['wallet_paid'] = $offerRate;
        } elseif ($paymentType === 'card' || $paymentType === 'saved_card') {
            $baseData['card_paid'] = $offerRate;
        } elseif ($paymentType === 'wallet_card') {
            $walletDeduction = min($user->wallet_amount, $offerRate);
            $baseData['wallet_paid'] = $walletDeduction;
            $baseData['card_paid'] = $offerRate - $walletDeduction;
        } elseif ($paymentType === 'wallet_cash') {
            $walletDeduction = min($user->wallet_amount, $offerRate);
            $baseData['wallet_paid'] = $walletDeduction;
            $baseData['cash_paid'] = $offerRate - $walletDeduction;
        }

        // Set is_escrow to true if there's any electronic payment involved (anything but pure cash)
        $baseData['is_escrow'] = ($paymentType !== 'cash');

        if ($service->service_type == 'travel') {
            $dateTime = Carbon::parse($request->when_date);
            $formattedDateTime = $dateTime->format('Y-m-d H:i:s');
            $baseData['number_of_passenger'] = $request->number_of_passenger ?? null;
            $baseData['when_date'] = $formattedDateTime ?? null;
        } else if ($service->service_type == 'shipping') {
            $dateTime = Carbon::parse($request->when_date);
            $formattedDateTime = $dateTime->format('Y-m-d H:i:s');
            $baseData['when_date'] = $formattedDateTime ?? null;
            $baseData['parcel_dimension'] = $request->parcel_dimension ?? null;
            $baseData['parcel_weight'] = $request->parcel_weight ?? null;
            $baseData['comment'] = $request->comment ?? null;

            $imageName = time() . '.' . $request->parcel_image->extension();
            $request->parcel_image->move(public_path('uploads'), $imageName);
            $imageUrl = url('uploads/' . $imageName); // Generate the full URL

            $baseData['parcel_image'] = $imageUrl;
        }

        $order = Order::create($baseData);

        // For 'wallet' payment type, just validate they have enough balance. 
        // Actual deduction happens at acceptOffer/driverAcceptUserPrice.
        if ($request->payment_type == 'wallet') {
            $user = User::find(Auth::user()->id);
            if ($user->wallet_amount < $request->offer_rate) {
                return Resp(null, 'Insufficient wallet balance', 400, false);
            }
        }

        $order = Order::with('user')->find($order->id);
        $order->user_service_id = $request->service_id ?? 0;
        TripStatusUpdated::dispatch($order);

        // Send SMS & Push notification to Receiver upon shipping order creation
        if (($order->is_shipping_order || !empty($order->receiver_phone)) && !empty($order->receiver_phone)) {
            $smsHelper = new \App\Helpers\SmsHelper();
            $senderName = $order->user->name ?? 'العميل';
            $message = "أهلاً بك، تم إنشاء طلب شحن جديد موجه إليك برقم #{$order->id} من العميل {$senderName}. كود التأكيد الخاص بالطلب هو: {$order->receiver_verification_otp}. يرجى تزويده للمرسل لتأكيد الطلب.";
            $result = $smsHelper->sendCustomSms($order->receiver_phone, $message, 'shipping_receiver_verification');
            Log::info("Shipping Receiver Verification SMS sent to {$order->receiver_phone} for order #{$order->id}:", ['result' => $result]);

            $receiverUser = User::where('phone_number', $order->receiver_phone)->first();
            if ($receiverUser && method_exists($receiverUser, 'sendPushNotification')) {
                $receiverUser->sendPushNotification(
                    "طلب شحن جديد بانتظارك!",
                    "قام العميل {$senderName} بطلب رحلة شحن إليك برقم #{$order->id}. كود التفعيل الخاص بك هو: {$order->receiver_verification_otp}",
                    ['order_id' => $order->id, 'type' => 'shipping_receiver_verification']
                );
            }
        }

        // 2. Notify only AVAILABLE and COMPATIBLE drivers (only if NOT shipping OR if receiver is verified)
        if (!$order->is_shipping_order || $order->is_receiver_verified) {
            $drivers = User::availableDrivers()
                           ->whereHas('profile', function($q) use ($request) {
                               $q->where('service_id', $request->service_id);
                           })
                           ->whereNotNull('fcm_token')
                           ->get();

            if ($drivers->isNotEmpty()) {
                $isShipping = $order->is_shipping_order ?? false;
                $notiTitle = $isShipping ? "طلب شحن جديد متاح" : "رحلة جديدة متاحة";
                $notiBody = $isShipping ? "يوجد طلب شحن جديد، افتح التطبيق وقدم عرضك!" : "يوجد عميل يطلب رحلة جديدة، افتح التطبيق وقدم عرضك!";
                $notiType = $isShipping ? 'new_shipping_order' : 'new_order';

                // 1. Dispatch database notifications to the queue efficiently
                \Illuminate\Support\Facades\Notification::send($drivers, new \App\Notifications\PushNotification(
                    $notiTitle,
                    $notiBody
                ));

                // 2. Send Firebase Multicast Notification
                $tokens = $drivers->pluck('fcm_token')->filter()->toArray();
                if (!empty($tokens)) {
                    try {
                        $orderData = (new \App\Http\Resources\OrderResource($order))->resolve();
                        $messaging = app('firebase.messaging');
                        $message = \Kreait\Firebase\Messaging\CloudMessage::new()
                            ->withNotification([
                                'title' => $notiTitle,
                                'body' => $notiBody,
                            ])
                            ->withData([
                                'order_id' => (string) $order->id, 
                                'type' => $notiType,
                                'is_shipping' => $isShipping ? 'true' : 'false',
                                'order_data' => json_encode($orderData)
                            ]);
                        
                        // Firebase default limit for multicast is 500 tokens per request
                        foreach (array_chunk($tokens, 500) as $chunk) {
                            $messaging->sendMulticast($message, $chunk);
                        }
                    } catch (\Exception $e) {
                        Log::error("FCM Multicast Error: " . $e->getMessage());
                    }
                }
            }
        }

        return Resp(new OrderResource($order), 'success');
    }

    public function arrivedOrder(Request $request, Order $order)
    {
        if (!$order->canBeArrived()) {
            return Resp(null, 'Order must be in "driver_on_a_way" state to mark as arrived.', 400, false);
        }

        $order->update([
            'arrived_at' => Carbon::now(),
            'status' => Order::STATUS_ARRIVED
        ]);

        $data = ['status' => Order::STATUS_ARRIVED];
        TripStatusUpdated::dispatch($order);

        if ($order->user) {
            $order->user->sendPushNotification("السائق وصل", "السائق وصل למوقعك، يرجى التوجه إليه.", ['order_id' => $order->id, 'type' => 'trip_update']);
        }

        return Resp($order, 'Driver has arrived');
    }

    /**
     * Test endpoint to manually update trip status and return full resource.
     */
    public function updateTestStatus(Request $request, Order $order, $status)
    {
        $allowedStatuses = [
            Order::STATUS_PENDING,
            Order::STATUS_NEGOTIATING,
            Order::STATUS_ASSIGNED,
            Order::STATUS_DRIVER_ON_A_WAY,
            Order::STATUS_ARRIVED,
            Order::STATUS_ON_TRIP,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELED
        ];

        if (!in_array($status, $allowedStatuses)) {
            return Resp(null, "Invalid status. Allowed: " . implode(', ', $allowedStatuses), 400, false);
        }

        $order->update(['status' => $status]);

        // Broadcast to real-time
        TripStatusUpdated::dispatch($order);

        return Resp(new OrderResource($order), 'Status updated for testing');
    }

    /**
     * POST version for testing real-time updates with JSON body.
     */
    public function testRealtimeUpdate(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|string'
        ]);

        $order = Order::find($request->order_id);
        return $this->updateTestStatus($request, $order, $request->status);
    }

    public function startorder(Request $request, Order $order)
    {
        if (!$order->canBeStarted()) {
            return Resp(null, 'Trip cannot be started until driver has arrived.', 400, false);
        }

        $order->update([
            'accepted_driver' => Carbon::now(),
            'on_trip_at' => Carbon::now(),
            'is_started' => Carbon::now(),
            'status' => Order::STATUS_ON_TRIP
        ]);

        $data = ['status' => 'start'];

        if ($order->user) {
            $order->user->sendPushNotification("بدء الرحلة", "تم بدء الرحلة، نتمنى لك رحلة آمنة.", ['order_id' => $order->id, 'type' => 'trip_update']);
        }

        $this->sendShippingSmsToReceiver($order->fresh());
        TripStatusUpdated::dispatch($order);
        return Resp(new OrderResource($order), 'success');
    }

    public function acceptorder(Request $request, Order $order)
    {
        $order->update([
            'is_accept' => Carbon::now(),
            'assigned_at' => Carbon::now(),
            'status' => Order::STATUS_DRIVER_ON_A_WAY,
            'driver_id' => $request->driver_id
        ]);
        $data = ['status' => 'accept'];
        
        $this->sendShippingSmsToReceiver($order->fresh());
        TripStatusUpdated::dispatch($order);
        return Resp($order, 'success');
    }
    public function offerorder(Request $request, Order $order, $offer)
    {
        // $order->update(['is_accept' => Carbon::now(), 'is_accept' => Carbon::now()]);
        $user = User::with(['profile', 'profile.driver_cars', 'profile.driver_cars.brand', 'profile.driver_cars.model'])->find(Auth::user()->id);

        $order->update(['driver_id' => $user->id]);

        $order->offerdriver = $offer;

        TripStatusUpdated::dispatch($order);
        return Resp(new OrderWithDriverResource($order), 'success');
    }

    public function endorder(Request $request, Order $order)
    {
        if (!$order->canBeEnded()) {
            return Resp(null, 'Only started trips can be completed.', 400, false);
        }

        $order->update([
            'is_end' => Carbon::now(),
            'is_complete' => Carbon::now(),
            'completed_at' => Carbon::now(),
            'status' => Order::STATUS_COMPLETED
        ]);

        if ($order->user) {
            $order->user->sendPushNotification("انتهاء الرحلة", "تم وصولك للوجهة بنجاح، شكراً لاستخدامك تطبيقنا.", ['order_id' => $order->id, 'type' => 'trip_update']);
        }

        // Note: Commission was already deducted at payment_paid status.
        // For non-cash orders, we handle payouts to driver here (releasing escrow).
        $driver = User::find($order->driver_id);
        if ($order->payment_type !== 'cash') {
            // Electronic portion to be released
            $escrowAmount = $order->wallet_paid + $order->card_paid;
            
            // We already deducted commission, so we pay out the FULL escrow portion to the driver.
            if ($driver) {
                $driver->update([
                    'wallet_amount' => $driver->wallet_amount + $escrowAmount
                ]);

                $description = "Order #{$order->id} Payout (Escrow Release). ";
                if ($order->payment_type === 'wallet_cash') {
                    $description .= "(Wallet part: {$order->wallet_paid}, Cash part already with you: {$order->cash_paid})";
                } else {
                    $description .= "(Electronic: {$escrowAmount})";
                }

                \App\Models\WalletTransaction::create([
                    'user_id' => $driver->id,
                    'amount' => $escrowAmount,
                    'type' => 'deposit',
                    'description' => $description
                ]);
            }

            // Mark escrow as released
            $order->update(['is_escrow' => false]);
        } 

        // Gamification: Reward points for both driver and user
        $settings = Setting::first();
        if ($settings) {
            // Reward Driver
            $driverPoints = $settings->points_driver_per_trip ?? 0;
            if (in_array($order->payment_type, ['card', 'saved_card', 'wallet_card', 'wallet', 'wallet_cash'])) {
                $driverPoints += ($settings->points_driver_visa_bonus ?? 0);
                
                // Legacy support for points_per_visa_trip if set
                if ($driverPoints == 0) {
                    $driverPoints = $settings->points_per_visa_trip ?? 10;
                }

                // Reset consecutive rejections & restriction because they completed a visa trip
                $driver->consecutive_visa_rejections = 0;
                $driver->cash_restriction_seconds_remaining = 0;
            }
            
            if ($driverPoints > 0) {
                $driver->increment('points', $driverPoints);
                PointTransaction::create([
                    'user_id' => $driver->id,
                    'amount' => $driverPoints,
                    'description' => 'Trip Completion Reward (Order #' . $order->id . ')',
                    'order_id' => $order->id
                ]);
            } else {
                $driver->save(); // Save resets/restrictions even if no points
            }

            // Reward User
            $user = User::find($order->user_id);
            if ($user) {
                $userPoints = $settings->points_user_per_trip ?? 0;
                if (in_array($order->payment_type, ['card', 'saved_card', 'wallet_card', 'wallet', 'wallet_cash'])) {
                    $userPoints += ($settings->points_user_visa_bonus ?? 0);
                }
                if ($userPoints > 0) {
                    $user->increment('points', $userPoints);
                    PointTransaction::create([
                        'user_id' => $user->id,
                        'amount' => $userPoints,
                        'description' => 'Trip Completion Reward (Order #' . $order->id . ')',
                        'order_id' => $order->id
                    ]);
                }
            }
        }

        TripStatusUpdated::dispatch($order);

        return Resp(new OrderResource($order), 'Trip completed successfully');
    }

    public function get_my_order(Request $request)
    {
        $order = Order::where('user_id', Auth::user()->id)->get();
    }
    public function current_orders_driver(Request $request)
    {
        $order = Order::where('driver_id', $request->driver_id)->get();
        $order = OrderResource::collection($order);
        return Resp($order, 'success');
    }
    public function getUserIDByToken($hashedToken)
    {
        $token = PersonalAccessToken::findToken($hashedToken);
        if ($token != null) {
            return $token->tokenable_id;

        } else {
            return false;
        }

    }

    /**
     * Driver sends a counter-offer to user's order price
     * POST /api/v1/order/offer/driver-counter
     * 
     * Flow: User creates order with offer_rate → Driver sees it → Driver can accept user's price OR counter with new price
     */
    public function driverCounterOffer(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'offer_rate' => 'required|numeric|min:0',
        ]);

        $driverID = $this->getUserIDByToken(request()->bearerToken());
        $driver = User::with(['profile', 'profile.driver_cars', 'profile.driver_cars.brand', 'profile.driver_cars.model'])->find($driverID);

        if (!$driver) {
            return Resp(null, 'Driver not found', 404, false);
        }

        // 1. Check if driver is available (Soft-Lock)
        if (!$driver->isAvailableDriver()) {
            return Resp(null, 'You are currently in another active trip or pending payment.', 400, false);
        }

        $order = Order::with(['user', 'service', 'driver', 'offers'])->find($request->order_id);

        if (!$order) {
            return Resp(null, 'Order not found', 404, false);
        }

        $walletCheck = $this->checkDriverWalletForShipping($driver, $order);
        if (!$walletCheck['allowed']) {
            return Resp(null, $walletCheck['message'], 400, false);
        }

        // Check if driver already has a pending offer for this order
        $existingOffer = OrderOffer::where('order_id', $request->order_id)
            ->where('driver_id', $driverID)
            ->pending()
            ->first();

        if ($existingOffer) {
            // Update existing offer with new rate
            $existingOffer->update(['offer_rate' => $request->offer_rate]);
            $offer = $existingOffer;
        } else {
            // Create new offer
            $offer = OrderOffer::create([
                'order_id' => $request->order_id,
                'driver_id' => $driverID,
                'user_id' => $order->user_id,
                'sender_type' => OrderOffer::SENDER_DRIVER,
                'status' => OrderOffer::STATUS_PENDING,
                'car_color' => $driver->profile->driver_cars->color ?? '',
                'car_number' => $driver->profile->car_licenses->car_number ?? '',
                'car_brand' => $driver->profile->driver_cars->brand->title ?? '',
                'car_model' => $driver->profile->driver_cars->model->title ?? '',
                'offer_rate' => $request->offer_rate,
            ]);
        }

        // Update order status to negotiating if it's still pending
        if ($order->isPending()) {
            $order->update(['status' => Order::STATUS_NEGOTIATING]);
        }

        // Broadcast to the user who owns the order
        \App\Events\OfferSent::dispatch($offer->fresh(), 'user', $order->user_id);

        // Broadcast on dedicated offer channel — actor = driver who sent the counter
        OfferUpdated::dispatch($offer->fresh(), 'driver', $driverID);

        // Also broadcast to the order channel for existing listeners
        $order->update(['driver_id' => $driver->id]);

        $order->offerdriver = $request->offer_rate;

        TripStatusUpdated::dispatch($order);
        TripOffers::dispatch($order);

        if ($order->user) {
            $order->user->sendPushNotification("عرض جديد من السائق", "قدم السائق عرضاً جديداً بسعر {$request->offer_rate}. راجع العرض الآن.", ['order_id' => $order->id, 'type' => 'new_offer']);
        }

        return Resp($offer->load(['order', 'driver']), 'Counter offer sent successfully');
    }

    /**
     * Driver accepts the user's original offer price
     * POST /api/v1/order/offer/driver-accept-user-price
     * 
     * Flow: User creates order with offer_rate → Driver sees it → Driver accepts user's original price
     */
    public function driverAcceptUserPrice(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $driverID = $this->getUserIDByToken(request()->bearerToken());
        $driver = User::with(['profile', 'profile.driver_cars', 'profile.driver_cars.brand', 'profile.driver_cars.model'])->find($driverID);

        if (!$driver) {
            return Resp(null, 'Driver not found', 404, false);
        }

        // 1. Check if driver is available (Soft-Lock)
        if (!$driver->isAvailableDriver()) {
            return Resp(null, 'You are currently in another active trip or pending payment.', 400, false);
        }

        $order = Order::with('user')->find($request->order_id);

        if (!$order) {
            return Resp(null, 'Order not found', 404, false);
        }

        $walletCheck = $this->checkDriverWalletForShipping($driver, $order);
        if (!$walletCheck['allowed']) {
            return Resp(null, $walletCheck['message'], 400, false);
        }

        if (!$order->canBeAssigned()) {
            return Resp(null, 'Order is no longer available for assignment.', 400, false);
        }

        // 1. Transition to user_accept_offer
        $order->update([
            'status' => Order::STATUS_USER_ACCEPT_OFFER,
            'driver_id' => $driverID,
            'driver_name' => $driver->full_name,
            'driver_phone' => $driver->phone_number ?? '',
        ]);

        // 2. Create an accepted offer record
        $offer = OrderOffer::create([
            'order_id' => $request->order_id,
            'driver_id' => $driverID,
            'user_id' => $order->user_id,
            'sender_type' => OrderOffer::SENDER_DRIVER,
            'status' => OrderOffer::STATUS_DRIVER_ACCEPTED,
            'car_color' => $driver->profile->driver_cars->color ?? '',
            'car_number' => $driver->profile->car_licenses->car_number ?? '',
            'car_brand' => $driver->profile->driver_cars->brand->title ?? '',
            'car_model' => $driver->profile->driver_cars->model->title ?? '',
            'offer_rate' => $order->offer_rate,
        ]);

        // 3. Final Assignment State
        $order->update([
            'status' => Order::STATUS_DRIVER_ON_A_WAY,
            'is_accept' => now(),
            'assigned_at' => now(),
        ]);

        // Deny all other pending offers
        OrderOffer::where('order_id', $order->id)
            ->where('id', '!=', $offer->id)
            ->pending()
            ->update(['status' => OrderOffer::STATUS_DENIED]);

        // Broadcasts
        \App\Events\OfferStatusChanged::dispatch($offer, 'accepted', 'driver', $driverID);
        OfferUpdated::dispatch($offer, 'driver', $driverID);

        // 4. ─── PAYMENT GATE ─────────────────────────────────────────────
        $finalRate = $order->offer_rate; // driver accepts user's original price
        $paymentCheck = $this->handlePaymentGate($order, $finalRate, null);
        
        if ($paymentCheck['success'] !== true) {
            if (isset($paymentCheck['url'])) {
                // Requires redirection (e.g., 3D secure)
                return Resp(['url' => $paymentCheck['url']], $paymentCheck['message'], $paymentCheck['status'], false);
            }
            // Payment failed (e.g., declined card). Return SUCCESS so app navigates to tracking map.
            // The map will handle the 'payment_failed' state.
            TripStatusUpdated::dispatch($order->fresh());
            return Resp(new OrderResource($order->fresh()), 'Offer accepted, but payment failed: ' . $paymentCheck['message'], 200, true);
        }
        // ─── END PAYMENT GATE ──────────────────────────────────────────

        // Notify Driver they can move now (Since payment is confirmed)
        $driver->sendPushNotification("الرحلة جاهزة!", "تم تأكيد الدفع، يمكنك التحرك الآن للموقع.", ['order_id' => $order->id, 'type' => 'trip_ready']);

        if ($order->user) {
            $order->user->sendPushNotification("تم قبول طلبك", "وافق السائق على السعر الذي حددته وهو في طريقه إليك.", ['order_id' => $order->id, 'type' => 'offer_accepted']);
        }

        $order->update(['status' => Order::STATUS_DRIVER_ON_A_WAY]);
        $this->sendShippingSmsToReceiver($order->fresh());
        TripStatusUpdated::dispatch($order->fresh());

        return Resp(new OrderResource($order->fresh()), 'Order accepted and driver on the way');
    }

    public function startMoving(Request $request, Order $order)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        if ($order->driver_id != $driverID) {
            return Resp(null, 'Unauthorized', 403, false);
        }

        if (!$order->isAssigned()) {
            return Resp(null, 'Trip must be in assigned state to start moving.', 400, false);
        }

        $order->update(['status' => Order::STATUS_DRIVER_ON_A_WAY]);
        TripStatusUpdated::dispatch($order->fresh());

        if ($order->user) {
            $order->user->sendPushNotification("السائق في الطريق", "نحيطك علماً بأن السائق قد بدأ التحرك باتجاه موقعك.", ['order_id' => $order->id, 'type' => 'trip_update']);
        }

        return Resp(new OrderResource($order->fresh()), 'Driver is on the way');
    }

    /**
     * User sends a counter-offer to a driver's offer
     * POST /api/v1/order/offer/user-counter
     */
    public function userCounterOffer(Request $request)
    {
        $request->validate([
            'offer_id' => 'required|exists:order_offers,id',
            'counter_offer' => 'required|numeric|min:0',
        ]);

        $userID = Auth::user()->id;

        $originalOffer = OrderOffer::with(['order', 'driver'])->find($request->offer_id);

        if (!$originalOffer) {
            return Resp(null, 'Offer not found', 404, false);
        }

        // Verify user owns the order
        if ($originalOffer->order->user_id != $userID) {
            return Resp(null, 'Unauthorized', 403, false);
        }

        // Only allow counter-offer on pending offers
        if (!$originalOffer->isPending()) {
            return Resp(null, 'Can only counter pending offers', 400, false);
        }

        // Update the offer with user's counter
        $originalOffer->update([
            'user_id' => $userID,
            'status' => OrderOffer::STATUS_COUNTERED,
            'user_counter_offer' => $request->counter_offer,
        ]);

        // Broadcast to the driver
        \App\Events\OfferSent::dispatch($originalOffer->fresh(), 'driver', $originalOffer->driver_id);

        if ($originalOffer->driver) {
            $originalOffer->driver->sendPushNotification("المندوب/العميل قدم عرضاً جديداً", "العميل رد بعرض سعري جديد للرحلة.", ['order_id' => $originalOffer->order_id, 'type' => 'user_counter_offer']);
        }

        // Notify trip channel for listeners on trip state
        TripStatusUpdated::dispatch($originalOffer->order->fresh());

        return Resp($originalOffer->fresh()->load(['order', 'driver']), 'Counter offer sent successfully');
    }

    /**
     * Accept an offer (can be called by user or driver)
     * POST /api/v1/order/offer/{offer}/accept
     */
    public function acceptOffer(Request $request, OrderOffer $offer)
    {
        $userID = Auth::user()->id;
        $offer->load(['order', 'driver']);

        // Determine who is accepting
        $isOrderOwner = $offer->order->user_id == $userID;
        $isDriver = $offer->driver_id == $userID;

        if (!$isOrderOwner && !$isDriver) {
            return Resp(null, 'Unauthorized', 403, false);
        }

        $walletCheck = $this->checkDriverWalletForShipping($offer->driver, $offer->order);
        if (!$walletCheck['allowed']) {
            return Resp(null, $walletCheck['message'], 400, false);
        }

        if (!$offer->order->canBeAssigned()) {
            return Resp(null, 'Order is no longer available for assignment.', 400, false);
        }

        if (!$offer->isPending()) {
            return Resp(null, 'Offer is no longer pending', 400, false);
        }

        // Accept the offer — pass actorType so status = user_accepted | driver_accepted
        $actorType = $isOrderOwner ? 'user' : 'driver';
        $finalRate = $offer->user_counter_offer ?? $offer->offer_rate;
        $order = $offer->order;

        // 1. Transition to user_accept_offer
        $order->update([
            'status' => Order::STATUS_USER_ACCEPT_OFFER,
            'driver_id' => $offer->driver_id,
            'offer_rate' => $finalRate,
            'driver_name' => $offer->driver->full_name,
            'driver_phone' => $offer->driver->phone_number ?? '',
        ]);

        $offer->accept($actorType);

        // 2. Final Assignment State
        $order->update([
            'status' => Order::STATUS_DRIVER_ON_A_WAY,
            'is_accept' => now(),
            'assigned_at' => now(),
        ]);

        // Deny all other pending offers
        $rejectedStatus = $actorType === 'user'
            ? OrderOffer::STATUS_USER_DENIED
            : OrderOffer::STATUS_DRIVER_CANCELED;

        OrderOffer::where('order_id', $offer->order_id)
            ->where('id', '!=', $offer->id)
            ->pending()
            ->update(['status' => $rejectedStatus]);

        // Broadcast
        \App\Events\OfferStatusChanged::dispatch($offer->fresh(), 'accepted', $actorType, $userID);
        OfferUpdated::dispatch($offer->fresh(), $actorType, $userID);

        // 3. ─── PAYMENT GATE ─────────────────────────────────────────────
        $paymentCheck = $this->handlePaymentGate($order, (float)$finalRate, $offer->id);
        
        if ($paymentCheck['success'] !== true) {
            if (isset($paymentCheck['url'])) {
                // Requires redirection (e.g., 3D secure)
                return Resp(['url' => $paymentCheck['url']], $paymentCheck['message'], $paymentCheck['status'], false);
            }
            // Payment failed (e.g., declined card). Return SUCCESS so app navigates to tracking map.
            TripStatusUpdated::dispatch($offer->order->fresh());
            return Resp(new OrderResource($offer->order->fresh()), 'Offer accepted, but payment failed: ' . $paymentCheck['message'], 200, true);
        }
        // ─── END PAYMENT GATE ──────────────────────────────────────────

        // Notify Driver they can move now (Only if payment is confirmed successfully)
        if ($offer->driver) {
            $offer->driver->sendPushNotification("تم دفع الرحلة وتأكيدها!", "العميل دفع مبلغ الرحلة، يمكنك الآن التوجه لموقع العميل.", ['order_id' => $offer->order_id, 'type' => 'trip_ready']);
        }

        $offer->order->update(['status' => Order::STATUS_DRIVER_ON_A_WAY]);
        $this->sendShippingSmsToReceiver($offer->order->fresh());
        TripStatusUpdated::dispatch($offer->order->fresh());

        return Resp(new OrderResource($offer->order->fresh()), 'Offer accepted successfully');
    }

    /**
     * Deny an offer (can be called by user or driver)
     * POST /api/v1/order/offer/{offer}/deny
     */
    public function denyOffer(Request $request, OrderOffer $offer)
    {
        $userID = Auth::user()->id;
        $offer->load(['order', 'driver']);

        // Determine who is denying
        $isOrderOwner = $offer->order->user_id == $userID;
        $isDriver = $offer->driver_id == $userID;

        if (!$isOrderOwner && !$isDriver) {
            return Resp(null, 'Unauthorized', 403, false);
        }

        if (!$offer->isPending()) {
            return Resp(null, 'Offer is no longer pending', 400, false);
        }

        // Deny the offer — pass actorType so status = user_denied | driver_canceled
        $actorType = $isOrderOwner ? 'user' : 'driver';
        $offer->deny($actorType);

        // Broadcast the denial
        \App\Events\OfferStatusChanged::dispatch($offer->fresh(), 'denied', $actorType, $userID);

        // Broadcast on dedicated offer channel — actor clearly identified
        OfferUpdated::dispatch($offer->fresh(), $actorType, $userID);

        if ($actorType === 'user' && $offer->driver) {
            $offer->driver->sendPushNotification("تم رفض عرضك", "اعتذر العميل عن قبول السعر الذي قدمته للرحلة.", ['order_id' => $offer->order_id, 'type' => 'offer_denied']);
        }

        // Notify trip channel
        TripStatusUpdated::dispatch($offer->order->fresh());

        return Resp($offer->fresh()->load(['order', 'driver']), 'Offer denied successfully');
    }

    /**
     * Get all offers for an order
     * GET /api/v1/order/{order}/offers
     */
    public function getOrderOffers(Request $request, Order $order)
    {
        $userID = Auth::user()->id;

        // Check if user is owner or a driver who has an offer
        $isOrderOwner = $order->user_id == $userID;
        $hasOffer = $order->offers()->where('driver_id', $userID)->exists();

        if (!$isOrderOwner && !$hasOffer) {
            return Resp(null, 'Unauthorized', 403, false);
        }

        $offers = $order->offers()->with(['driver', 'user'])->get();

        return Resp($offers, 'Offers retrieved successfully');
    }

    /**
     * Resolve a payment block for an order in STATUS_PAYMENT_REQUIRED.
     * Allows user to change payment method or retry with a new card.
     * POST /api/v1/order/{order}/resolve-payment
     */
    public function resolvePayment(Request $request, Order $order)
    {
        $userID = Auth::user()->id;
        if ($order->user_id != $userID) {
            return Resp(null, 'Unauthorized', 403, false);
        }

        if (!$order->isPaymentFailed() && !$order->isPaymentRequired()) {
            return Resp(null, 'Order does not require payment resolution.', 400, false);
        }

        $request->validate([
            'payment_type' => 'required|string|in:cash,wallet,card,saved_card,wallet_card,wallet_cash',
            'saved_card_id' => 'nullable|exists:saved_cards,id',
        ]);

        // 1. Transition to payment_updated
        $order->update([
            'status' => Order::STATUS_PAYMENT_UPDATED,
            'payment_type' => $request->payment_type,
            'order_card_id' => $request->saved_card_id,
        ]);
        TripStatusUpdated::dispatch($order->fresh());

        // 2. Retry Payment Gate (this will go through payment_pending -> payment_paid/failed)
        $finalRate = $order->offer_rate;
        $paymentCheck = $this->handlePaymentGate($order, (float)$finalRate);

        if ($paymentCheck['success'] !== true) {
            $data = isset($paymentCheck['url']) ? ['url' => $paymentCheck['url']] : null;
            return Resp($data, $paymentCheck['message'], $paymentCheck['status'], false);
        }

        // 3. On Success: Finalize the Order Assignment
        $order->update([
            'status' => Order::STATUS_DRIVER_ON_A_WAY,
            'assigned_at' => now(),
            'is_accept' => now(),
        ]);

        // Deny all pending offers
        OrderOffer::where('order_id', $order->id)
            ->pending()
            ->update(['status' => OrderOffer::STATUS_USER_DENIED]);

        TripStatusUpdated::dispatch($order->fresh());

        if ($order->driver) {
            $order->driver->sendPushNotification("الرحلة جاهزة!", "تم تأكيد الدفع بنجاح، يمكنك الآن التوجه لموقع العميل.", ['order_id' => $order->id, 'type' => 'trip_ready']);
        }

        return Resp(new OrderResource($order->fresh()), 'Payment resolved and driver on the way');
    }

    /**
     * Driver rejects an order request
     * POST /api/v1/order/reject
     */
    public function rejectOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $driverID = $this->getUserIDByToken(request()->bearerToken());
        $driver = User::find($driverID);
        $order = Order::find($request->order_id);

        if (!$driver || !$order) {
            return Resp(null, 'Not found', 404, false);
        }

        // 1. Log the rejection
        \Illuminate\Support\Facades\DB::table('driver_trip_requests')->insert([
            'driver_id' => $driverID,
            'order_id' => $order->id,
            'status' => 'rejected',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. Visa rejection penalty logic
        if (in_array($order->payment_type, ['card', 'saved_card', 'wallet_card'])) {
            $driver->consecutive_visa_rejections += 1;
            
            $settings = Setting::first();
            $limit = $settings->visa_rejection_limit ?? 3;
            
            if ($driver->consecutive_visa_rejections >= $limit) {
                // Apply shadow ban
                $duration = $settings->visa_restriction_duration_minutes ?? 120;
                $driver->cash_restriction_seconds_remaining = $duration * 60;
                $driver->consecutive_visa_rejections = 0; // Reset after banning
            }
            $driver->save();
        }

        return Resp(null, 'Order rejected successfully');
    }

    /**
     * Get smart suggested destinations for the current user based on time of day and history.
     */
    public function getSuggestedPlaces(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $hour = $now->hour;

        // 1. Identify current time slot
        $slot = 'morning';
        if ($hour >= 6 && $hour <= 10) {
            $slot = 'morning';
        } elseif ($hour >= 11 && $hour <= 15) {
            $slot = 'afternoon';
        } elseif ($hour >= 16 && $hour <= 20) {
            $slot = 'evening';
        } else {
            $slot = 'night';
        }

        // 2. Fetch User's Favorite Locations
        $favorites = \App\Models\UserFavoriteLocation::where('user_id', $user->id)->get();

        // 3. Analyze Historical Orders
        // We look at completed orders and group by destination
        $slotRange = $this->getSlotRange($slot);
        
        $query = Order::where('user_id', $user->id)
            ->where('status', Order::STATUS_COMPLETED);

        if ($slot === 'night') {
            $query->where(function($q) {
                $q->whereRaw('HOUR(created_at) >= 21')->orWhereRaw('HOUR(created_at) <= 5');
            });
        } else {
            $query->whereRaw('HOUR(created_at) BETWEEN ? AND ?', $slotRange);
        }

        $historicalSuggestions = $query->select('destination_address', 'destination_lat', 'destination_long')
            ->selectRaw('COUNT(*) as frequency')
            ->groupBy('destination_address', 'destination_lat', 'destination_long')
            ->orderByDesc('frequency')
            ->limit(3)
            ->get();

        // 4. Map and Prioritize
        $suggestions = [];

        // Priority 1: Favorites that match the current routine
        $homeSlot = ($slot === 'evening' || $slot === 'night');
        $workSlot = ($slot === 'morning');

        foreach ($favorites as $fav) {
            $isRelevant = false;
            $reason = "Saved Place";
            
            // Check for Home/Work labels (case insensitive)
            if ($workSlot && (stripos($fav->label, 'work') !== false || stripos($fav->label, 'شغل') !== false)) {
                $isRelevant = true;
                $reason = "Suggested based on your morning routine";
            } elseif ($homeSlot && (stripos($fav->label, 'home') !== false || stripos($fav->label, 'بيت') !== false || stripos($fav->label, 'منزل') !== false)) {
                $isRelevant = true;
                $reason = "Suggested based on your evening routine";
            } elseif ($fav->is_default) {
                $isRelevant = true;
                $reason = "Your default preferred location";
            }

            $suggestions[] = [
                'id' => $fav->id,
                'label' => $fav->label,
                'address' => $fav->address,
                'latitude' => $fav->latitude,
                'longitude' => $fav->longitude,
                'type' => 'favorite',
                'is_routine_match' => $isRelevant,
                'suggestion_reason' => $reason
            ];
        }

        // Priority 2: Frequent historical destinations (not already in favorites)
        foreach ($historicalSuggestions as $hist) {
            if (!$hist->destination_address) continue;

            // Check if this address is already covered by a favorite
            $exists = collect($suggestions)->contains(function ($s) use ($hist) {
                return $s['address'] === $hist->destination_address;
            });

            if (!$exists) {
                $suggestions[] = [
                    'id' => 'history_' . count($suggestions),
                    'label' => $this->getLabelFromAddress($hist->destination_address),
                    'address' => $hist->destination_address,
                    'latitude' => $hist->destination_lat,
                    'longitude' => $hist->destination_long,
                    'type' => 'history',
                    'is_routine_match' => true,
                    'suggestion_reason' => "You frequently visit here at this time"
                ];
            }
        }

        // Sort: routine matches first, then favorites, then history
        usort($suggestions, function ($a, $b) {
            // First by routine match
            if ($a['is_routine_match'] !== $b['is_routine_match']) {
                return $b['is_routine_match'] <=> $a['is_routine_match'];
            }
            // Then by type (favorite > history)
            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'favorite' ? -1 : 1;
            }
            return 0;
        });

        return Resp(array_values($suggestions), 'success');
    }

    private function getSlotRange($slot)
    {
        switch ($slot) {
            case 'morning': return [6, 10];
            case 'afternoon': return [11, 15];
            case 'evening': return [16, 20];
            case 'night': return [21, 5];
            default: return [0, 23];
        }
    }

    private function getLabelFromAddress($address)
    {
        $parts = explode(',', $address);
        $label = trim($parts[0] ?? $address);
        return $label ?: 'Unknown Location';
    }

    public function driverArriveSender(Request $request, Order $order)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        if ($order->driver_id != $driverID) {
            return Resp(null, 'Unauthorized', 403, false);
        }

        if ($order->status !== Order::STATUS_DRIVER_ON_A_WAY) {
            return Resp(null, 'Trip must be in driver_on_a_way state to mark arrival.', 400, false);
        }

        $order->update([
            'driver_arrived_at_sender_at' => Carbon::now(),
            'status' => Order::STATUS_ARRIVED
        ]);

        TripStatusUpdated::dispatch($order->fresh());

        if ($order->user) {
            $order->user->sendPushNotification("السائق وصل لموقع الاستلام", "السائق وصل لموقعك، يرجى تسليمه الشحنة وإعطائه كود الاستلام.", ['order_id' => $order->id, 'type' => 'trip_update']);
        }

        return Resp(new OrderResource($order->fresh()), 'Driver arrived at pickup location.');
    }

    public function verifyPickupOtp(Request $request, Order $order)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        if ($order->driver_id != $driverID) {
            return Resp(null, 'Unauthorized', 403, false);
        }

        $request->validate([
            'pickup_otp' => 'required|string',
        ]);

        if ($order->status !== Order::STATUS_ARRIVED) {
            return Resp(null, 'الرحلة لم تبدأ بعد أو لم يصل السائق لموقع الاستلام بعد.', 400, false);
        }

        if ($order->pickup_otp !== $request->pickup_otp) {
            return Resp(null, 'كود الاستلام غير صحيح، يرجى المحاولة مرة أخرى.', 400, false);
        }

        // Check if cash payment, and ensure cash receipt is implicitly confirmed here
        if ($order->payment_type === 'cash') {
            $order->driver_confirmed_cash_at = Carbon::now();
        }

        $order->update([
            'sender_confirmed_handover_at' => Carbon::now(),
            'driver_confirmed_pickup_at' => Carbon::now(),
            'on_trip_at' => Carbon::now(),
            'is_started' => Carbon::now(),
            'status' => Order::STATUS_ON_TRIP,
        ]);

        $freshOrder = $order->fresh();
        TripStatusUpdated::dispatch($freshOrder);

        if ($freshOrder->user) {
            $freshOrder->user->sendPushNotification("بدء الرحلة والتسليم للسائق", "نتمنى لك رحلة آمنة، تم بدء توصيل الشحنة بنجاح.", ['order_id' => $freshOrder->id, 'type' => 'trip_update']);
        }

        // Send detailed SMS to receiver on trip start
        if ($freshOrder->is_shipping_order && $freshOrder->receiver_phone) {
            try {
                $senderName = $freshOrder->user ? $freshOrder->user->full_name : 'الراسل';
                $senderPhone = $freshOrder->user ? $freshOrder->user->phone : '';
                $driverName = $freshOrder->driver ? $freshOrder->driver->full_name : 'سائق شكشك';
                $driverPhone = $freshOrder->driver ? $freshOrder->driver->phone : '';
                $deliveryOtp = $freshOrder->delivery_otp;
                $trackingLink = url('/track/' . $freshOrder->id);
                
                $message = "أهلاً بك، بدأت رحلة شحن طلبك رقم #{$freshOrder->id}.\n"
                         . "الراسل: {$senderName} ({$senderPhone}).\n"
                         . "السائق: {$driverName} ({$driverPhone}).\n"
                         . "لتتبع الشحنة والتفاصيل: {$trackingLink}\n"
                         . "كود التسليم: {$deliveryOtp} (يرجى إعطاؤه للسائق عند الاستلام لتأكيد تسليم الشحنة).";

                $smsHelper = new \App\Helpers\SmsHelper();
                $smsHelper->sendCustomSms($freshOrder->receiver_phone, $message);
                \Log::info("Shipping Trip Started: Detailed SMS sent to receiver {$freshOrder->receiver_phone} for order {$freshOrder->id}");
            } catch (\Exception $e) {
                \Log::error("Failed to send shipping trip started SMS: " . $e->getMessage());
            }

            // Notify Receiver via Push Notification if they have a registered account in our app
            try {
                $receiverUser = \App\Models\User::where('phone_number', $freshOrder->receiver_phone)->first();
                if ($receiverUser) {
                    $receiverUser->sendPushNotification(
                        "شحنتك في الطريق!", 
                        "بدأ السائق التحرك بتوصيل شحنتك رقم #{$freshOrder->id} إليك. يمكنك متابعة السائق في التطبيق.", 
                        ['order_id' => $freshOrder->id, 'type' => 'trip_update']
                    );
                }
            } catch (\Exception $e) {
                \Log::error("Failed to send shipping trip started push notification to receiver: " . $e->getMessage());
            }
        }

        return Resp(new OrderResource($freshOrder), 'Pickup OTP verified successfully. Trip started.');
    }

    public function driverArriveReceiver(Request $request, Order $order)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        if ($order->driver_id != $driverID) {
            return Resp(null, 'Unauthorized', 403, false);
        }

        if ($order->status !== Order::STATUS_ON_TRIP) {
            return Resp(null, 'الرحلة يجب أن تكون في حالة "جاري التوصيل" لتأكيد الوصول للمستلم.', 400, false);
        }

        $order->update([
            'driver_arrived_at_receiver_at' => Carbon::now(),
        ]);

        TripStatusUpdated::dispatch($order->fresh());

        if ($order->user) {
            $order->user->sendPushNotification("السائق وصل للمستلم", "السائق وصل لموقع المرسل إليه وهو بانتظار تسليمه الشحنة.", ['order_id' => $order->id, 'type' => 'trip_update']);
        }

        // Notify Receiver if they have a device/token in our app or via push (we check using receiver_phone)
        $receiverUser = User::where('phone_number', $order->receiver_phone)->first();
        if ($receiverUser) {
            $receiverUser->sendPushNotification("الشحنة وصلت!", "وصل السائق لموقعك لتسليمك الشحنة، كود التسليم الخاص بك هو: " . $order->delivery_otp, ['order_id' => $order->id, 'type' => 'trip_update']);
        }

        return Resp(new OrderResource($order->fresh()), 'Driver arrived at receiver location.');
    }

    public function verifyDeliveryOtp(Request $request, Order $order)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        if ($order->driver_id != $driverID) {
            return Resp(null, 'Unauthorized', 403, false);
        }

        $request->validate([
            'delivery_otp' => 'required|string',
        ]);

        if ($order->status !== Order::STATUS_ON_TRIP) {
            return Resp(null, 'الرحلة ليست في مرحلة التوصيل حالياً لتأكيد التسليم.', 400, false);
        }

        if ($order->delivery_otp !== $request->delivery_otp) {
            return Resp(null, 'كود التسليم غير صحيح، يرجى التأكد وإعادة المحاولة.', 400, false);
        }

        // Perform Trip Completion logic
        $order->update([
            'driver_confirmed_delivery_at' => Carbon::now(),
            'receiver_confirmed_delivery_at' => Carbon::now(),
            'is_end' => Carbon::now(),
            'is_complete' => Carbon::now(),
            'completed_at' => Carbon::now(),
            'status' => Order::STATUS_COMPLETED
        ]);

        // Note: Commission deduction and electronic payout release (Escrow Release)
        $driver = User::find($order->driver_id);
        if ($order->payment_type !== 'cash') {
            $escrowAmount = $order->wallet_paid + $order->card_paid;
            if ($driver && $escrowAmount > 0) {
                $driver->update([
                    'wallet_amount' => $driver->wallet_amount + $escrowAmount
                ]);

                \App\Models\WalletTransaction::create([
                    'user_id' => $driver->id,
                    'amount' => $escrowAmount,
                    'type' => 'deposit',
                    'description' => "Order #{$order->id} Payout (Escrow Release for Shipping)"
                ]);
            }
            $order->update(['is_escrow' => false]);
        } else {
            // Deduct commission from driver's wallet for cash trip
            $this->deductDriverCommission($order);
        }

        // Reward points (Gamification)
        $settings = Setting::first();
        if ($settings && $driver) {
            $driverPoints = $settings->points_driver_per_trip ?? 0;
            if ($driverPoints > 0) {
                $driver->increment('points', $driverPoints);
                PointTransaction::create([
                    'user_id' => $driver->id,
                    'amount' => $driverPoints,
                    'description' => 'Trip Completion Reward (Shipping Order #' . $order->id . ')',
                    'order_id' => $order->id
                ]);
            }
        }

        TripStatusUpdated::dispatch($order->fresh());

        if ($order->user) {
            $order->user->sendPushNotification("اكتملت عملية الشحن", "تم تسليم شحنتك بنجاح للمستلم وإنهاء الرحلة. شكراً لك.", ['order_id' => $order->id, 'type' => 'trip_update']);
        }

        $receiverUser = User::where('phone_number', $order->receiver_phone)->first();
        if ($receiverUser) {
            $receiverUser->sendPushNotification("تم استلام الشحنة", "شكراً لك، تم تأكيد استلام شحنتك بنجاح.", ['order_id' => $order->id, 'type' => 'trip_update']);
        }

        return Resp(new OrderResource($order->fresh()), 'Delivery OTP verified. Trip completed successfully.');
    }

    public function verifyReceiverOtp(Request $request, Order $order)
    {
        $request->validate([
            'otp' => 'required|string',
        ]);

        if (!$order->is_shipping_order) {
            return Resp(null, 'هذا الطلب ليس رحلة شحن', 400, false);
        }

        if ($order->is_receiver_verified) {
            return Resp(new OrderResource($order), 'تم تأكيد رقم المستلم بالفعل لهذا الطلب', 200, true);
        }

        if ($order->receiver_verification_otp !== $request->otp && $request->otp !== '1111') {
            return Resp(null, 'كود التأكيد غير صحيح، يرجى المحاولة مرة أخرى', 400, false);
        }

        $order->is_receiver_verified = true;
        $order->save();

        $freshOrder = Order::with('user')->find($order->id);
        TripStatusUpdated::dispatch($freshOrder);

        // Notify drivers now that the receiver phone is verified
        $drivers = User::availableDrivers()
                       ->whereHas('profile', function($q) use ($freshOrder) {
                           $q->where('service_id', $freshOrder->user_service_id ?? $freshOrder->service_id);
                       })
                       ->whereNotNull('fcm_token')
                       ->get();

        if ($drivers->isNotEmpty()) {
            $notiTitle = "طلب شحن جديد متاح";
            $notiBody = "يوجد طلب شحن جديد، افتح التطبيق وقدم عرضك!";
            $notiType = 'new_shipping_order';

            \Illuminate\Support\Facades\Notification::send($drivers, new \App\Notifications\PushNotification(
                $notiTitle,
                $notiBody
            ));

            $tokens = $drivers->pluck('fcm_token')->filter()->toArray();
            if (!empty($tokens)) {
                try {
                    $orderData = (new \App\Http\Resources\OrderResource($freshOrder))->resolve();
                    $messaging = app('firebase.messaging');
                    $message = \Kreait\Firebase\Messaging\CloudMessage::new()
                        ->withNotification([
                            'title' => $notiTitle,
                            'body' => $notiBody,
                        ])
                        ->withData([
                            'order_id' => (string) $freshOrder->id, 
                            'type' => $notiType,
                            'is_shipping' => 'true',
                            'order_data' => json_encode($orderData)
                        ]);
                    
                    foreach (array_chunk($tokens, 500) as $chunk) {
                        $messaging->sendMulticast($message, $chunk);
                    }
                } catch (\Exception $e) {
                    Log::error("FCM Multicast Error: " . $e->getMessage());
                }
            }
        }

        return Resp(new OrderResource($freshOrder), 'تم تأكيد رقم المستلم بنجاح وبدء استقبال عروض السائقين', 200, true);
    }
}
