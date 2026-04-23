<?php

namespace App\Services;

use App\Models\User;
use App\Models\SavedCard;
use App\Models\PaymentTransaction;
use App\Events\PaymentStatusUpdated;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymobService
{
    protected $apiKey;
    protected $secretKey;
    protected $publicKey;
    protected $hmacSecret;
    protected $baseUrl;
    protected $apiUrl;
    protected $mode;

    public function __construct()
    {
        $this->apiKey = config('paymob.api_key');
        $this->secretKey = config('paymob.secret_key');
        $this->publicKey = config('paymob.public_key');
        $this->hmacSecret = config('paymob.hmac_secret');
        $this->baseUrl = config('paymob.base_url');
        $this->apiUrl = config('paymob.api_url');
        $this->mode = config('paymob.mode');
    }

    /**
     * Create a payment intention for card payment with optional tokenization
     *
     * @param User $user
     * @param float $amount Amount in EGP
     * @param bool $saveCard Whether to save the card for future payments
     * @param array $billingData Optional billing data
     * @return array
     * @throws Exception
     */
    public function createPaymentIntention(User $user, float $amount, bool $saveCard = false, array $billingData = [], ?int $orderId = null): array
    {
        try {
            $amountCents = (int) ($amount * 100);

            // Prepare billing data
            $billing = array_merge([
                'first_name' => $user->name ?? 'Customer',
                'last_name' => $user->name ?? 'Customer',
                'email' => $user->email ?? 'customer@example.com',
                'phone_number' => $user->phone_number ?? '+201000000000',
                'country' => 'EG',
                'city' => 'Cairo',
                'street' => 'N/A',
                'building' => 'N/A',
                'floor' => 'N/A',
                'apartment' => 'N/A',
            ], $billingData);

            $integrationId = config('paymob.integration_id');
            $walletIntegrationId = config('paymob.wallet_integration_id');

            // Build payment methods — always include card, add wallet if configured
            $paymentMethods = [];
            if ($integrationId) {
                $paymentMethods[] = (int) $integrationId;        // Card (Visa/Mastercard)
            }
            if ($walletIntegrationId) {
                $paymentMethods[] = (int) $walletIntegrationId;  // E-wallets (Vodafone Cash, Orange, etc.)
            }
            if (empty($paymentMethods)) {
                $paymentMethods = ['card'];  // fallback
            }

            $payload = [
                'amount' => $amountCents,
                'currency' => config('paymob.currency', 'EGP'),
                'payment_methods' => $paymentMethods,
                'billing_data' => $billing,
                'customer' => [
                    'first_name' => $billing['first_name'],
                    'last_name' => $billing['last_name'],
                    'email' => $billing['email'],
                ],
                'extras' => [
                    'user_id' => (string) $user->id,
                    'order_id' => (string) ($orderId ?? ''),   // ← link to our order
                ],
                'notification_url' => url('/api/v1/paymob/webhook'),
                'redirection_url' => url('/payment/complete'),
            ];

            if ($saveCard) {
                $payload['save_card'] = true;
                $payload['special_save_card'] = true; // Required by some Paymob v1 versions
            }

            // Log outgoing payload for debugging
            Log::info('Creating Paymob intention', [
                'url' => $this->baseUrl,
                'save_card_requested' => $saveCard,
                'payload_keys' => array_keys($payload),
                // Avoid logging full payload to protect PII, but log keys and critical flags
                'save_card_in_payload' => $payload['save_card'] ?? 'not-set',
                'special_save_card_in_payload' => $payload['special_save_card'] ?? 'not-set',
                'notification_url' => $payload['notification_url'],
                'redirection_url' => $payload['redirection_url'],
            ]);

            // Make API request to create intention
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Token ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, $payload);

            if (!$response->successful()) {
                Log::error('Paymob intention creation failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                throw new Exception('Failed to create payment intention: ' . $response->body());
            }

            $data = $response->json();

            // Create a pending transaction record
            PaymentTransaction::create([
                'payment_id' => $data['payment_keys'][0]['order_id'] ?? $data['id'] ?? null,
                'status' => 'pending',
                'success' => 0,
                'amount' => $amount,
                'payment_method' => 'card',
                'payment_gateway' => 'paymob',
                'userID' => $user->id,
                'note' => $saveCard ? 'Card tokenization payment' : 'One-time payment',
            ]);

            // Log full Paymob response for debugging (moved after DB create for robustness)
            Log::info('Paymob intention response', [
                'status' => $response->status(),
                'id' => $data['id'] ?? null,
                'client_secret' => substr($data['client_secret'] ?? '', 0, 20) . '...',
                'payment_keys' => $data['payment_keys'] ?? [],
            ]);

            // Build the unified checkout URL (Paymob Intention API v1)
            $checkoutUrl = 'https://accept.paymob.com/unifiedcheckout/?publicKey='
                . $this->publicKey
                . '&clientSecret=' . ($data['client_secret'] ?? '');

            return [
                'success' => true,
                'intention_id' => $data['id'] ?? null,
                'client_secret' => $data['client_secret'] ?? null,
                'public_key' => $this->publicKey,
                'integration_id' => config('paymob.integration_id'),
                'payment_keys' => $data['payment_keys'] ?? [],
                'order_id' => $data['id'] ?? null,
                'checkout_url' => $checkoutUrl,   // ← open this in browser / webview
            ];

        } catch (Exception $e) {
            Log::error('Paymob createPaymentIntention error', [
                'user_id' => $user->id,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle Paymob webhook callback — main orchestrator
     * Delegates each responsibility to a focused private method.
     *
     * @param array $data Raw webhook payload from Paymob
     * @return array
     */
    public function handleWebhook(array $data): array
    {
        try {
            Log::info('[Webhook] [0] handleWebhook started', ['type' => $data['type'] ?? 'TRANSACTION', 'keys' => array_keys($data)]);
            // 1. Parse the raw payload into a normalised context array
            $ctx = $this->extractWebhookContext($data);

            // 2. Update PaymentTransaction status and credit the user wallet
            $this->updateTransactionAndWallet($ctx);

            // 3. Save card token if present
            $this->handleCardTokenization($data, $ctx);

            // 4. Mark order as paid and auto-assign driver when needed
            $this->linkOrderAndAssignDriver($ctx);

            return [
                'success' => true,
                'transaction_id' => $ctx['transactionId'],
                'order_id' => $ctx['orderId'],
                'status' => $ctx['success'] ? 'success' : 'failed',
            ];

        } catch (Exception $e) {
            Log::error('Paymob webhook handling error', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    //  PRIVATE WEBHOOK HELPERS
    // ─────────────────────────────────────────────────────────────────────

    /**
     * 1. Parse any Paymob webhook payload (old-style / Intention API / TOKEN)
     *    into a flat, normalised context array used by the other helpers.
     */
    private function extractWebhookContext(array $data): array
    {
        Log::info('[Webhook] [1] extractWebhookContext started', ['type' => $data['type'] ?? 'TRANSACTION', 'isIntentionApi' => !isset($data['obj']), 'data' => $data]);
        $isTokenWebhook = ($data['type'] ?? '') === 'TOKEN';
        $isIntentionApi = !isset($data['obj']);   // Intention API has no 'obj' key at root

        $obj = $data['obj'] ?? $data;

        // ── success flag ──────────────────────────────────────────────────
        $success = $obj['success'] ?? $obj['transaction']['success'] ?? false;
        if (config('paymob.mode') === 'test') {
            $success = false;    // TESTING PURPOSES ONLY: Force webhook failure
        }
        if ($isTokenWebhook) {
            $success = true;    // TOKEN type always means card was verified
        }

        // ── amount ────────────────────────────────────────────────────────
        $amountCents = $isTokenWebhook
            ? 100                                               // 1 EGP card-verification charge
            : ($obj['amount_cents']                             // old-style TRANSACTION
                ?? $obj['transaction']['amount_cents']          // Intention API TRANSACTION
                ?? 0);

        // ── Paymob transaction / order IDs ───────────────────────────────
        $transactionId = $obj['id'] ?? null;

        $orderId = null;
        if (isset($obj['payment_keys'][0]['order_id'])) {
            $orderId = $obj['payment_keys'][0]['order_id'];
        }

        if ($isTokenWebhook) {
            // Use numeric order_id first — this matches payment_id stored in payment_transactions.
            // next_payment_intention (pi_test_xxx) is a fallback.
            $orderId = $obj['order_id'] ?? $obj['next_payment_intention'] ?? null;
        } elseif ($isIntentionApi) {
            // DB stores `payment_keys[0].order_id` (numeric) as payment_id — match that first
            $orderId = $orderId
                ?? $obj['transaction']['order']['id']   // numeric — matches DB payment_id
                ?? $obj['intention']['id']              // pi_test_xxx — fallback
                ?? null;
        } else {
            $orderId = $orderId
                ?? $obj['transaction']['order']['id']
                ?? $obj['order']['id']
                ?? null;
        }

        // ── user ID ───────────────────────────────────────────────────────
        $userId = $obj['intention']['extras']['creation_extras']['user_id']  // Intention API
            ?? $obj['extras']['creation_extras']['user_id']                  // old-style
            ?? $obj['extras']['user_id']
            ?? $obj['order']['extras']['user_id']
            ?? $obj['order']['merchant_order_id']
            ?? null;

        // For TOKEN webhooks: look up userId via the PaymentTransaction (most accurate).
        // Falls back to email-based lookup only if transaction not found.
        if ($isTokenWebhook) {
            $tokenTx = null;
            if ($orderId) {
                $tokenTx = PaymentTransaction::where('payment_id', $orderId)->first();
            }
            if (!$tokenTx && !empty($obj['next_payment_intention'])) {
                $tokenTx = PaymentTransaction::where('payment_id', $obj['next_payment_intention'])->first();
            }
            if ($tokenTx) {
                $userId = $tokenTx->userID;
            } elseif (!$userId && isset($obj['email'])) {
                $user = User::where('email', $obj['email'])->first();
                if ($user)
                    $userId = $user->id;
            }
        }

        // ── our internal order ID (links payment → ride) ──────────────────
        $ourOrderId = $obj['intention']['extras']['creation_extras']['order_id']
            ?? $obj['extras']['creation_extras']['order_id']
            ?? $obj['extras']['order_id']
            ?? $obj['order']['extras']['order_id']
            ?? null;

        return compact(
            'obj',
            'isTokenWebhook',
            'isIntentionApi',
            'success',
            'amountCents',
            'transactionId',
            'orderId',
            'userId',
            'ourOrderId'
        ) + ['amount' => $amountCents / 100];
    }

    /**
     * 2. Persist a PaymentTransaction status update (success / failed)
     *    and — if the payment just succeeded — credit the user's wallet.
     */
    private function updateTransactionAndWallet(array $ctx): void
    {
        Log::info('[Webhook] [2] updateTransactionAndWallet started', ['orderId' => $ctx['orderId'], 'userId' => $ctx['userId'], 'amount' => $ctx['amount'], 'success' => $ctx['success'], 'amountCents' => $ctx['amountCents']]);
        $transaction = PaymentTransaction::where('payment_id', $ctx['orderId'])->first();

        if (!$transaction) {
            // TOKEN webhooks only save the card — wallet is credited by the TRANSACTION webhook.
            if ($ctx['isTokenWebhook']) {
                Log::info('[Webhook] [2] TOKEN webhook — skipping wallet credit (card-only)', [
                    'userId' => $ctx['userId'],
                ]);
            } else {
                Log::warning('Paymob webhook: transaction not found', ['order_id' => $ctx['orderId']]);
            }
            return;
        }

        $wasPending = ($transaction->status !== 'success');

        $transaction->update([
            'status' => $ctx['success'] ? 'success' : 'failed',
            'success' => $ctx['success'] ? 1 : 0,
        ]);

        if ($ctx['success'] && $wasPending) {
            $user = User::find($transaction->userID ?? $ctx['userId']);
            if ($user) {
                $user->update([
                    'wallet_amount' => $user->wallet_amount + $ctx['amount'],
                ]);
            } else {
                Log::warning('Paymob webhook: user not found for wallet update', [
                    'user_id' => $ctx['userId'],
                    'transaction_userId' => $transaction->userID,
                ]);
            }
        }

        // Broadcast real-time status update (WebSocket / Pusher)
        try {
            event(new PaymentStatusUpdated($transaction));
        } catch (\Exception $e) {
            Log::error('Paymob webhook broadcast error: ' . $e->getMessage());
        }
    }

    /**
     * 3. Persist card token to saved_cards table.
     *    Two sub-cases:
     *    a) Token embedded in a TRANSACTION webhook (source_data.token)
     *    b) Standalone TOKEN webhook with its own payload structure
     */
    private function handleCardTokenization(array $data, array $ctx): void
    {
        Log::info('[Webhook] [3] handleCardTokenization started', ['isTokenWebhook' => $ctx['isTokenWebhook'], 'hasSourceToken' => isset($ctx['obj']['source_data']['token']), 'hasObjToken' => isset($ctx['obj']['token'])]);
        if ($ctx['success'] && isset($ctx['obj']['source_data']['token'])) {
            // (a) card token came with a TRANSACTION webhook
            $this->saveCardToken($ctx['obj'], $ctx['userId']);
        } elseif ($ctx['isTokenWebhook'] && isset($ctx['obj']['token'])) {
            // (b) standalone TOKEN webhook
            $this->saveCardTokenFromTokenPayload($ctx['obj'], $ctx['userId']);
        }
    }

    /**
     * 4. Mark the linked ride / order as PAID and auto-assign the driver
     *    if an accepted offer is waiting.
     */
    private function linkOrderAndAssignDriver(array $ctx): void
    {
        Log::info('[Webhook] [4] linkOrderAndAssignDriver started', ['ourOrderId' => $ctx['ourOrderId'], 'success' => $ctx['success']]);
        if (!$ctx['ourOrderId']) {
            return;
        }

        $order = \App\Models\Order::find($ctx['ourOrderId']);

        if (!$order || $order->payment_status === \App\Models\Order::PAYMENT_PAID) {
            return;
        }

        if (!$ctx['success']) {
            $order->update([
                'payment_status' => \App\Models\Order::PAYMENT_FAILED,
                'status' => \App\Models\Order::STATUS_PAYMENT_FAILED,
            ]);
            \App\Events\TripStatusUpdated::dispatch($order->fresh());
            return;
        }

        $order->update(['payment_status' => \App\Models\Order::PAYMENT_PAID]);

        $pendingOffer = $order->offers()->where('status', 'driver_accepted')->latest()->first()
            ?? $order->offers()->where('status', 'user_accepted')->latest()->first();

        if ($pendingOffer && $order->status === \App\Models\Order::STATUS_NEGOTIATING) {
            $order->update([
                'driver_id' => $pendingOffer->driver_id,
                'offer_rate' => $pendingOffer->user_counter_offer ?? $pendingOffer->offer_rate,
                'status' => \App\Models\Order::STATUS_DRIVER_ON_A_WAY,
                'is_accept' => now(),
                'assigned_at' => now(),
            ]);

            // Notify Driver
            if ($order->driver) {
                $order->driver->sendPushNotification("الرحلة جاهزة للتحرك!", "تم تأكيد دفع العميل بنجاح، يمكنك الآن التوجه لموقع العميل.", ['order_id' => $order->id, 'type' => 'trip_ready']);
            }

            \App\Events\TripStatusUpdated::dispatch($order->fresh());
        }
    }


    /**
     * Save card token from successful transaction
     *
     * @param array $transactionData
     * @param int|string|null $userId
     * @return SavedCard|null
     */
    protected function saveCardToken(array $transactionData, $userId): ?SavedCard
    {
        if (!$userId) {
            Log::warning('Cannot save card token: no user ID provided');
            return null;
        }

        $sourceData = $transactionData['source_data'] ?? [];
        $token = $sourceData['token'] ?? null;

        if (!$token) {
            return null;
        }

        // Check if card already exists for this user
        $existingCard = SavedCard::where('user_id', $userId)
            ->where('card_token', $token)
            ->first();

        if ($existingCard) {
            return $existingCard;
        }

        // Check if user has any cards - if not, this will be default
        $hasCards = SavedCard::where('user_id', $userId)->exists();

        $savedCard = SavedCard::create([
            'user_id' => $userId,
            'card_token' => $token,
            'card_subtype' => $sourceData['sub_type'] ?? $sourceData['type'] ?? 'CARD',
            'masked_pan' => '**** ' . ($sourceData['pan'] ?? '****'),
            'is_default' => !$hasCards, // First card is default
            'card_holder_name' => $transactionData['source_data']['owner_name'] ?? null,
            'expiry_month' => $sourceData['exp_month'] ?? null,
            'expiry_year' => $sourceData['exp_year'] ?? null,
            'paymob_order_id' => $transactionData['order']['id'] ?? null,
            'paymob_transaction_id' => $transactionData['id'] ?? null,
        ]);

        Log::info('Card token saved successfully', [
            'user_id' => $userId,
            'card_id' => $savedCard->id,
            'masked_pan' => $savedCard->masked_pan,
        ]);

        return $savedCard;
    }

    /**
     * Save card token from a direct TOKEN webhook payload
     *
     * @param array $tokenObj
     * @return SavedCard|null
     */
    protected function saveCardTokenFromTokenPayload(array $tokenObj, $userId = null): ?SavedCard
    {
        $token = $tokenObj['token'] ?? null;
        if (!$token) {
            return null;
        }

        // If userId not provided, try to identify user from the transaction order_id or intention
        if (!$userId) {
            $transaction = null;
            if (!empty($tokenObj['next_payment_intention'])) {
                $transaction = PaymentTransaction::where('payment_id', $tokenObj['next_payment_intention'])->first();
            }

            if (!$transaction && !empty($tokenObj['order_id'])) {
                $transaction = PaymentTransaction::where('payment_id', $tokenObj['order_id'])->first();
            }

            if ($transaction) {
                $userId = $transaction->userID;
                Log::info('Identified user from PaymentTransaction', ['userId' => $userId, 'payment_id' => $transaction->payment_id]);
            }

            // Fallback to email only if transaction not found
            if (!$userId && !empty($tokenObj['email'])) {
                $user = User::where('email', $tokenObj['email'])->first();
                if ($user) {
                    $userId = $user->id;
                    Log::info('Identified user from Email fallback', ['userId' => $userId, 'email' => $tokenObj['email']]);
                }
            }
        } else {
            Log::info('Using userId from webhook context', ['userId' => $userId]);
        }

        if (!$userId) {
            Log::warning('Cannot save card token (TOKEN webhook): no user ID found for token payload', ['payload' => $tokenObj]);
            return null;
        }

        $existingCard = SavedCard::where('user_id', $userId)
            ->where('card_token', $token)
            ->first();

        if ($existingCard) {
            return $existingCard;
        }

        $hasCards = SavedCard::where('user_id', $userId)->exists();

        $maskedPan = $tokenObj['masked_pan'] ?? '****';
        $lastFourDigits = substr($maskedPan, -4);

        $cardHolderName = $tokenObj['source_data']['owner_name']
            ?? $tokenObj['source_data']['name']
            ?? "$lastFourDigits";


        $savedCard = SavedCard::create([
            'user_id' => $userId,
            'card_token' => $token,
            'card_subtype' => $tokenObj['card_subtype'] ?? 'CARD',
            'masked_pan' => $tokenObj['masked_pan'] ?? '****',
            'is_default' => !$hasCards,
            'card_holder_name' => $cardHolderName,
            'expiry_month' => $tokenObj['expiry_month'] ?? null,
            'expiry_year' => $tokenObj['expiry_year'] ?? null,
            'paymob_order_id' => $tokenObj['order_id'] ?? null,
            'paymob_transaction_id' => $tokenObj['id'] ?? null,
        ]);

        Log::info('Card token saved successfully via TOKEN webhook', [
            'user_id' => $userId,
            'card_id' => $savedCard->id,
            'masked_pan' => $savedCard->masked_pan,
        ]);

        return $savedCard;
    }

    /**
     * Pay using a saved card token
     *
     * @param SavedCard $card
     * @param float $amount Amount in EGP
     * @param int|null $orderId Optional order ID for ride payment
     * @return array
     * @throws Exception
     */
    public function payWithSavedCard(SavedCard $card, float $amount, ?int $orderId = null): array
    {
        try {
            $user = $card->user;
            $amountCents = (int) ($amount * 100);

            // Step 1: Authenticate and get token
            $authToken = $this->authenticate();

            // Step 2: Create order
            $paymobOrder = $this->createOrder($authToken, $amountCents, $user->id);

            // Step 3: Get payment key for token payment
            $paymentKey = $this->getPaymentKey($authToken, $paymobOrder['id'], $amountCents, $user, $card->card_token);

            // Step 4: Process payment with saved token
            $response = Http::timeout(30)->post($this->apiUrl . '/acceptance/payments/pay', [
                'source' => [
                    'identifier' => $card->card_token,
                    'subtype' => 'TOKEN',
                ],
                'payment_token' => $paymentKey,
            ]);

            if (!$response->successful()) {
                throw new Exception('Token payment failed: ' . ($response->json()['message'] ?? 'Unknown error'));
            }

            $paymentData = $response->json();
            $success = $paymentData['success'] ?? false;

            // Record transaction
            PaymentTransaction::create([
                'payment_id' => $paymobOrder['id'],
                'status' => $success ? 'success' : 'failed',
                'success' => $success ? 1 : 0,
                'amount' => $amount,
                'payment_method' => 'saved_card',
                'payment_gateway' => 'paymob',
                'userID' => $user->id,
                'note' => 'Payment with saved card',
            ]);

            if (!$success) {
                throw new Exception('Payment declined by the payment gateway.');
            }

            // Credit wallet if payment succeeded
            if ($success) {
                $user->update([
                    'wallet_amount' => $user->wallet_amount + $amount,
                ]);
                Log::info('Wallet credited via saved card payment', [
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'new_balance' => $user->fresh()->wallet_amount,
                ]);
            }

            return [
                'success' => $success,
                'transaction_id' => $paymentData['id'] ?? null,
                'order_id' => $paymobOrder['id'],
                'amount' => $amount,
                'message' => $success ? 'Payment successful' : 'Payment failed',
            ];

        } catch (Exception $e) {
            Log::error('Paymob token payment error', [
                'card_id' => $card->id,
                'user_id' => $card->user_id,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Authenticate with Paymob and get auth token
     *
     * @return string
     * @throws Exception
     */
    protected function authenticate(): string
    {
        $response = Http::timeout(30)->post($this->apiUrl . '/auth/tokens', [
            'api_key' => $this->apiKey,
        ]);

        if (!$response->successful()) {
            throw new Exception('Paymob authentication failed');
        }

        return $response->json()['token'];
    }

    /**
     * Create an order in Paymob
     *
     * @param string $authToken
     * @param int $amountCents
     * @param int $merchantOrderId
     * @return array
     * @throws Exception
     */
    protected function createOrder(string $authToken, int $amountCents, int $merchantOrderId): array
    {
        $response = Http::timeout(30)->post($this->apiUrl . '/ecommerce/orders', [
            'auth_token' => $authToken,
            'delivery_needed' => false,
            'amount_cents' => $amountCents,
            'currency' => config('paymob.currency', 'EGP'),
            'merchant_order_id' => $merchantOrderId . '_' . time(),
            'items' => [],
        ]);

        if (!$response->successful()) {
            throw new Exception('Failed to create Paymob order');
        }

        return $response->json();
    }

    /**
     * Get payment key for token payment
     *
     * @param string $authToken
     * @param int $orderId
     * @param int $amountCents
     * @param User $user
     * @param string $cardToken
     * @return string
     * @throws Exception
     */
    protected function getPaymentKey(string $authToken, int $orderId, int $amountCents, User $user, string $cardToken): string
    {
        $response = Http::timeout(30)->post($this->apiUrl . '/acceptance/payment_keys', [
            'auth_token' => $authToken,
            'order_id' => $orderId,
            'amount_cents' => $amountCents,
            'currency' => config('paymob.currency', 'EGP'),
            'expiration' => 3600,
            'integration_id' => config('paymob.integration_id'),
            'billing_data' => [
                'first_name' => $user->name ?? 'Customer',
                'last_name' => $user->name ?? 'Customer',
                'email' => $user->email ?? 'customer@example.com',
                'phone_number' => $user->phone_number ?? '+201000000000',
                'country' => 'EG',
                'city' => 'Cairo',
                'street' => 'N/A',
                'building' => 'N/A',
                'floor' => 'N/A',
                'apartment' => 'N/A',
                'state' => 'N/A',
                'postal_code' => 'N/A',
                'shipping_method' => 'N/A',
            ],
            'token' => $cardToken,
        ]);

        if (!$response->successful()) {
            throw new Exception('Failed to get payment key');
        }

        return $response->json()['token'];
    }

    /**
     * Verify HMAC signature for webhook security
     *
     * @param string $signature
     * @param array $data
     * @return bool
     */
    public function verifyHmac(string $signature, array $data): bool
    {
        // Paymob HMAC calculation
        $obj = $data['obj'] ?? $data;

        $hmacString = ($obj['amount_cents'] ?? '') .
            ($obj['created_at'] ?? '') .
            ($obj['currency'] ?? '') .
            (($obj['error_occured'] ?? false) ? 'true' : 'false') .
            (($obj['has_parent_transaction'] ?? false) ? 'true' : 'false') .
            ($obj['id'] ?? '') .
            ($obj['integration_id'] ?? '') .
            (($obj['is_3d_secure'] ?? false) ? 'true' : 'false') .
            (($obj['is_auth'] ?? false) ? 'true' : 'false') .
            (($obj['is_capture'] ?? false) ? 'true' : 'false') .
            (($obj['is_refunded'] ?? false) ? 'true' : 'false') .
            (($obj['is_standalone_payment'] ?? false) ? 'true' : 'false') .
            (($obj['is_voided'] ?? false) ? 'true' : 'false') .
            ($obj['order']['id'] ?? '') .
            ($obj['owner'] ?? '') .
            (($obj['pending'] ?? false) ? 'true' : 'false') .
            ($obj['source_data']['pan'] ?? '') .
            ($obj['source_data']['sub_type'] ?? '') .
            ($obj['source_data']['type'] ?? '') .
            (($obj['success'] ?? false) ? 'true' : 'false');

        $calculatedHmac = hash_hmac('sha512', $hmacString, $this->hmacSecret);

        return hash_equals($calculatedHmac, $signature);
    }

    /**
     * Get user's saved cards
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserSavedCards(int $userId)
    {
        return SavedCard::forUser($userId)
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Delete a saved card
     *
     * @param int $cardId
     * @param int $userId
     * @return bool
     * @throws Exception
     */
    public function deleteSavedCard(int $cardId, int $userId): bool
    {
        $card = SavedCard::where('id', $cardId)
            ->where('user_id', $userId)
            ->first();

        if (!$card) {
            throw new Exception('Card not found');
        }

        $wasDefault = $card->is_default;
        $card->delete();

        // If deleted card was default, set another card as default
        if ($wasDefault) {
            $nextCard = SavedCard::where('user_id', $userId)->first();
            if ($nextCard) {
                $nextCard->setAsDefault();
            }
        }

        return true;
    }
}
