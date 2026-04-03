<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Events\TripStarted;
use App\Http\Resources\OutCityOffersCollection;
use App\Http\Resources\OutCityOffersResource;
use App\Models\OrderOffer;
use Gate;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Events\TripEnded;
use App\Events\TripCancel;
use App\Events\TripOffers;
use App\Events\TripCreated;
use App\Events\TripAccepted;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderWithDriverResource;
use Laravel\Sanctum\PersonalAccessToken;

class OrderApiController extends Controller
{
    public function get_driver_active_ride(Request $request)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        $order = Order::where('driver_id', $driverID)->whereIn('status', ['started'])->first();
        return Resp(new OrderResource($order), 'success');
    }
    public function get_user_active_ride(Request $request)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        if($request->in_city) {
            $order = Order::where('user_id', $driverID)
            ->where('inter_city', '=', 1)
            ->whereIn('status', ['started', 'searching'])->first();
        }
        return Resp(new OrderResource($order), 'success');
    }

    public function add_out_city_offer(Request $request, $order_id, $offer_rate)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        $driver = User::with(['profile', 'profile.driver_cars', 'profile.driver_cars.brand', 'profile.driver_cars.model'])->find($driverID);
        $order = Order::find($order_id);
        $offer = OrderOffer::create([
            'order_id'      => $order_id,
            'driver_id'     => $driverID,
            'car_color'     => $driver->profile->driver_cars->color  ?? '',
            'car_number'    => $driver->profile->car_licenses->car_number ?? '',
            'car_brand'     => $driver->profile->driver_cars->brand->title ?? '',
            'car_model'     => $driver->profile->driver_cars->model->title ?? '',
            'offer_rate'    => $offer_rate,
        
        ]);


        $order->offerdriver         = $offer_rate;
        $order->driver_name      = $driver->full_name;
        $order->driver_phone     = $driver->phone_number ?? '';
        $order->car_color        = $driver->profile->driver_cars->color  ?? '';
        $order->car_number       = $driver->profile->car_licenses->car_number ?? '';
        $order->car_brand        = $driver->profile->driver_cars->brand->title ?? '';
        $order->car_model        = $driver->profile->driver_cars->model->title ?? '';

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
        $response = distancematrix($request->origin, $request->destination);
        $km = $response['rows'][0]['elements'][0]['distance']['value'] / 1000;
        $result['km'] = $km;
        $result['price'] = number_format($km *  $Service->km_charge, 2);
        $result['min'] = number_format(($response['rows'][0]['elements'][0]['duration']['value'] / 60));
        return  $result;
    }

    public function get_driver_orders(Request $request)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());

        $orders =  Order::with('driver', 'user')->where('inter_city', $request->in_city)
        ->where('driver_id', $driverID)
        ->get();

        $statusArray = array_fill_keys(['searching', 'placed', 'started', 'completed', 'canceled'], []);

        // Populate status array with orders
        foreach ($orders as $order) {
            $statusArray[$order->status][] = new OrderResource($order);
        }
    
        return Resp($statusArray, 'success');
    }
    public function get_user_orders(Request $request)
    {
        $userID = $this->getUserIDByToken(request()->bearerToken());

        $orders =  Order::where('inter_city', $request->in_city)
        ->where('user_id', $userID)
        ->get();
        $statusArray = array_fill_keys(['searching', 'placed', 'started', 'completed', 'canceled'], []);

        // Populate status array with orders
        foreach ($orders as $order) {
            $statusArray[$order->status][] = new OrderResource($order);
        }
    

        return Resp($statusArray, 'success');
    }
    public function cancelorder(Request $request, Order $order)
    {
        $order->update(['is_canceled' => Carbon::now(), 'status' => 'canceled', 'canceled_by' => Auth::user()->id]);
    
        $data =['status' => 'canceled'];

        TripCancel::dispatch( $order, $data );

        return Resp($order, 'success');
    }

    public function neworder(StoreOrderRequest $request)
    {
        $service = Service::find($request->service_id);
        $baseData = [
            'service_id'        => $request->service_id ?? '',
            'driver_id'         => null,
            'distance'          => $request->distance ?? '',
            'distance_type'     => 'km',
            'destination_address'  => $request->destination_address ?? '',
            'destination_lat'   => $request->destination_lat ?? '',
            'destination_long'  => $request->destination_long ?? '',
            'source_address'    => $request->source_address ?? '',
            'source_lat'        => $request->source_lat ?? '',
            'source_long'       => $request->source_long ?? '',
            'offer_rate'        => $request->offer_rate ?? '0',
            'final_rate'        => $request->final_rate ?? '0',
            'status'            => 'searching',
            'user_id'           => Auth::user()->id,
            'inter_city'        => $request->inter_city,
        ];
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
            
            $imageName = time().'.'.$request->parcel_image->extension();
            $request->parcel_image->move(public_path('uploads'), $imageName);
            $imageUrl = url('uploads/'.$imageName); // Generate the full URL
            
            $baseData['parcel_weight'] = $imageUrl;


            
        }
        

        $order = Order::create($baseData);
        $order =  Order::with('user')->find($order->id);
        TripCreated::dispatch($order);
        return Resp(new OrderResource($order), 'success');
    }

    public function startorder(Request $request, Order $order)
    {
        $order->update(['accepted_driver' => Carbon::now(), 'status' => 'started']);
        
        $data =['status' => 'start'];

        TripStarted::dispatch($order, $data);
        return Resp($order, 'success');
    }

    public function acceptorder(Request $request, Order $order)
    {
        $order->update(['is_accept' => Carbon::now(), 'status' => 'placed', 'driver_id' => Auth::user()->id]);
        $data =['status' => 'accept'];
        TripAccepted::dispatch( $order, $data );
        return Resp($order, 'success');
    }
    public function offerorder(Request $request, Order $order, $offer)
    {
        // $order->update(['is_accept' => Carbon::now(), 'is_accept' => Carbon::now()]);
        $user = User::with(['profile', 'profile.driver_cars', 'profile.driver_cars.brand', 'profile.driver_cars.model'])->find(Auth::user()->id);

        $order->offerdriver = $offer;
        $order->driver_name      = $user->full_name;
        $order->driver_phone     = $user->phone_number ?? '';
        $order->car_color        = $user->profile->driver_cars->color  ?? '';
        $order->car_number       = $user->profile->car_licenses->car_number ?? '';
        $order->car_brand        = $user->profile->driver_cars->brand->title ?? '';
        $order->car_model        = $user->profile->driver_cars->model->title ?? '';

        TripOffers::dispatch($order);
        return Resp(new OrderWithDriverResource($order), 'success');
    }

    public function endorder(Request $request, Order $order)
    {
        $order->update(['is_end' => Carbon::now(),'status' => 'completed']);
        $data =['status' => 'end'];

        TripEnded::dispatch( $order, $data );

        return Resp('', 'success');
    }

    public function get_my_order(Request $request)
    {
        $order = Order::where('user_id', Auth::user()->id)->get();
    }
    public function current_orders_driver(Request $request)  
    {
        $order = Order::where('driver_id', $request->driver_id)->get();
        $order =  OrderResource::collection($order);
        return Resp($order, 'success');
    }
    public function getUserIDByToken($hashedToken)
    {
        $token = PersonalAccessToken::findToken($hashedToken);
        if($token != null) {
            return $token->tokenable_id;

        } else {
            return false;
        }

    }
}
