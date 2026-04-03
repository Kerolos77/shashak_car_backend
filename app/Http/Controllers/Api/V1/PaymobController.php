<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SavedCard;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymobController extends Controller
{
    protected $paymobService;

    public function __construct(PaymobService $paymobService)
    {
        $this->paymobService = $paymobService;
    }

    /**
     * Create payment intention (for saving card or direct payment)
     */
    public function createPaymentIntention(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'order_id' => 'nullable|exists:orders,id',   // link payment to the ride
            'save_card' => 'boolean',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|email',
            'phone_number' => 'nullable|string',
        ]);

        try {
            $user = $request->user();
            $amount = $request->amount;
            $saveCard = $request->boolean('save_card', false);
            $orderId = $request->order_id ? (int) $request->order_id : null;
            $billingData = $request->only(['first_name', 'last_name', 'email', 'phone_number']);

            $result = $this->paymobService->createPaymentIntention(
                $user,
                $amount,
                $saveCard,
                $billingData,
                $orderId
            );

            return response()->json([
                'success' => true, 
                'data' => $result,
                'message' => 'Payment intention created successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Dedicated method for just adding and saving a card
     * Generates a payment intention for a small amount (10 EGP) to verify the card
     */
    public function addCard(Request $request)
    {
        try {
            $user = $request->user();
            // Minimum 1 EGP usually required for some Paymob card methods
            $amount = 1.0;

            $result = $this->paymobService->createPaymentIntention(
                $user,
                $amount,
                true, // Force save card
                [],
                null // No order linked
            );

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Add card intention created successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Charge wallet with a custom amount.
     * The user opens the returned checkout_url in a WebView/browser,
     * completes the payment, and the webhook automatically credits the wallet.
     *
     * POST /paymob/charge-wallet
     * Body: { "amount": 150, "save_card": true }
     */
    public function chargeWallet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'save_card' => 'boolean',
        ]);

        try {
            $user = $request->user();
            $amount = (float) $request->amount;
            $saveCard = $request->boolean('save_card', false);

            $result = $this->paymobService->createPaymentIntention(
                $user,
                $amount,
                $saveCard,
                [],
                null
            );

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Payment intention created. Open checkout_url to complete payment.',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Handle Paymob Webhook
     */
    public function handleWebhook(Request $request)
    {
        Log::info('[Webhook] [Controller] Request received', ['payload' => $request->all()]);
        try {
            // Skip HMAC check on local env for easy testing
            if (config('app.env') !== 'local') {
                $hmac = $request->query('hmac');
                if (!$hmac || !$this->paymobService->verifyHmac($hmac, $request->all())) {
                    Log::warning('Paymob webhook HMAC verification failed', ['hmac' => $hmac]);
                    return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
                }
            }

            $result = $this->paymobService->handleWebhook($request->all());

            return response()->json($result);

        } catch (Exception $e) {
            Log::error('Webhook handling error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get user's saved cards
     */
    public function getSavedCards(Request $request)
    {
        try {
            $cards = $this->paymobService->getUserSavedCards($request->user()->id)
                ->each->makeVisible('card_token');

            return response()->json([
                'success' => true,
                'data' => $cards
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug and verify saved cards status
     */
    public function debugCards(Request $request)
    {
        try {
            $user = $request->user();
            $allCardsCount = SavedCard::count();
            $userCards = SavedCard::where('user_id', $user->id)->get();
            
            return response()->json([
                'success' => true,
                'debug' => [
                    'current_user_id' => $user->id,
                    'current_user_email' => $user->email,
                    'total_cards_in_db' => $allCardsCount,
                    'user_cards_count' => $userCards->count(),
                    'user_cards' => $userCards->makeVisible('card_token'),
                    'env' => config('app.env'),
                    'db_connection' => config('database.default'),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a saved card
     */
    public function deleteSavedCard(Request $request, $id)
    {
        try {
            $this->paymobService->deleteSavedCard($id, $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Card deleted successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Pay with a saved card token
     */
    public function payWithSavedCard(Request $request)
    {
        $request->validate([
            'saved_card_id' => 'required|exists:saved_cards,id',
            'amount' => 'required|numeric|min:1',
            'order_id' => 'nullable|integer'
        ]);

        try {
            $card = SavedCard::where('id', $request->saved_card_id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $result = $this->paymobService->payWithSavedCard(
                $card,
                $request->amount,
                $request->order_id
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Set a card as default
     */
    public function setDefaultCard(Request $request, $id)
    {
        try {
            $card = SavedCard::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $card->setAsDefault();

            return response()->json([
                'success' => true,
                'message' => 'Default card updated successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
