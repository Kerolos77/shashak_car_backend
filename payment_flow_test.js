/**
 * ============================================================
 *  SHAKSHAK — Full Payment Flow (Card / Saved Card)
 *  Base URL: http://127.0.0.1:8000/api/v1  (local)
 *           http://shakshak.net/api/v1     (production)
 *
 *  Run before testing:
 *    Terminal 1: php artisan serve --port=8000
 *    Terminal 2: npx @soketi/soketi start
 * ============================================================
 */


// ─────────────────────────────────────────────────────────
// STEP 1 — User Login  →  get user_token
// ─────────────────────────────────────────────────────────
{
    method: "POST",
        url: "/auth/send_otp",
            body: {
        "phone": "01000000001",
            "password": "password",
                "type": "user"
    },
    save: "user_token  ← from response.data.token"
}


// ─────────────────────────────────────────────────────────
// STEP 2 — Driver Login  →  get driver_token
// ─────────────────────────────────────────────────────────
{
    method: "POST",
        url: "/auth/send_otp",
            body: {
        "phone": "01000000002",
            "password": "password",
                "type": "driver"
    },
    save: "driver_token  ← from response.data.token"
}


// ─────────────────────────────────────────────────────────
// STEP 3 — User: Create Order (payment_type = card)
// Real-time: trip-{order_id} channel → TripStatusUpdated (searching)
// ─────────────────────────────────────────────────────────
{
    method: "POST",
        url: "/order/new",
            headers: { "Authorization": "Bearer {user_token}" },
    body: {
        "service_id": 1,
            "source_address": "ميدان التحرير، القاهرة",
                "source_lat": "30.0444",
                    "source_long": "31.2357",
                        "destination_address": "مطار القاهرة",
                            "destination_lat": "30.1127",
                                "destination_long": "31.4000",
                                    "offer_rate": "150",
                                        "distance": "18",
                                            "inter_city": 0,
                                                "is_female_only": false,
                                                    "payment_type": "card"   // card | saved_card | wallet | cash
    },
    save: "order_id  ← from response.data.id"
}


// ─────────────────────────────────────────────────────────
// STEP 4 — Driver: Send Counter Offer
// Real-time: user-{user_id} → offer.sent
//            offer-{offer_id} → OfferUpdated (pending)
// ─────────────────────────────────────────────────────────
{
    method: "POST",
        url: "/order/offer/driver-counter",
            headers: { "Authorization": "Bearer {driver_token}" },
    body: {
        "order_id": "{order_id}",
            "offer_rate": "180"
    },
    save: "offer_id  ← from response.data.id"
}


// ─────────────────────────────────────────────────────────
// STEP 5 — User: Accept Offer  →  PAYMENT GATE
// Real-time: offer-{offer_id} → OfferUpdated (user_accepted)
//
// Response depends on payment_type:
//   card       → 402 { payment_required: true, amount, order_id, offer_id }
//   saved_card → 200 { driver assigned immediately }
//   cash/wallet→ 200 { driver assigned immediately }
// ─────────────────────────────────────────────────────────
{
    method: "POST",
        url: "/order/offer/{offer_id}/accept",
            headers: { "Authorization": "Bearer {user_token}" },
    body: { },

    // Expected response for payment_type = "card":
    expected_response: {
        "success": false,
            "data": {
            "payment_required": true,
                "amount": 180,
                    "order_id": "{order_id}",
                        "offer_id": "{offer_id}"
        },
        "message": "Payment required to confirm booking"
    }
}


// ─────────────────────────────────────────────────────────
// STEP 6 — User: Create Payment Intention  (card only)
//   Flutter uses client_secret + public_key to open Paymob SDK
// ─────────────────────────────────────────────────────────
{
    method: "POST",
        url: "/paymob/payment-intention",
            headers: { "Authorization": "Bearer {user_token}" },
    body: {
        "order_id": "{order_id}",
            "amount": 180,
                "save_card": true    // true → show "Save Card" checkbox in Paymob SDK
    },
    expected_response: {
        "success": true,
            "data": {
            "client_secret": "...",
                "public_key": "egy_pk_test_...",
                    "intention_id": "...",
                        "integration_id": 5477653
        }
    }
}
// → Flutter opens Paymob SDK with client_secret
// → User enters card details, optionally ticks "Save Card"
// → Paymob calls webhook automatically after payment


// ─────────────────────────────────────────────────────────
// STEP 7 — Paymob Webhook  (Paymob calls this automatically)
//   For LOCAL testing: call it manually from Postman
//   HMAC can be skipped locally or generate correctly for prod
// Real-time: TripStatusUpdated (assigned) on trip-{order_id}
// ─────────────────────────────────────────────────────────
{
    method: "POST",
        url: "/paymob/webhook?hmac=SKIP_FOR_LOCAL_TEST",
            body: {
        "obj": {
            "success": true,
                "amount_cents": 18000,
                    "id": "txn_test_001",
                        "created_at": "2026-03-04T22:00:00Z",
                            "currency": "EGP",
                                "order": {
                "id": "paymob_order_001",
                    "extras": {
                    "user_id": "{user_id}",
                        "order_id": "{order_id}"   // ← this links payment → our order
                },
                "merchant_order_id": "{user_id}_1234567890"
            },
            "source_data": {
                "token": "card_token_abc123",   // saved if save_card=true
                    "pan": "1234",
                        "sub_type": "VISA",
                            "type": "card"
            },
            // HMAC fields (needed for production):
            "error_occured": false,
                "has_parent_transaction": false,
                    "integration_id": 5477653,
                        "is_3d_secure": true,
                            "is_auth": false,
                                "is_capture": false,
                                    "is_refunded": false,
                                        "is_standalone_payment": true,
                                            "is_voided": false,
                                                "owner": 123,
                                                    "pending": false
        }
    },
    expected_result: [
        "order.payment_status = 'paid'",
        "order.status = 'assigned'",
        "order.driver_id = {driver_id}",
        "saved_cards table gets new row (if save_card=true)",
        "TripStatusUpdated broadcast on trip-{order_id}"
    ]
}


// ─────────────────────────────────────────────────────────
// STEP 8 — Verify Order is Assigned
// ─────────────────────────────────────────────────────────
{
    method: "GET",
        url: "/order/{order_id}",   // or GET /user/get_driver_active_ride
            headers: { "Authorization": "Bearer {user_token}" },
    expected_response: {
        "status": "assigned",
            "payment_status": "paid",
                "driver_id": "{driver_id}"
    }
}


// ─────────────────────────────────────────────────────────
// STEP 9 — (Optional) Check Saved Cards
// ─────────────────────────────────────────────────────────
{
    method: "GET",
        url: "/paymob/saved-cards",
            headers: { "Authorization": "Bearer {user_token}" },
    expected_response: [
        {
            "id": 1,
            "card_subtype": "VISA",
            "masked_pan": "**** 1234",
            "is_default": true,
            "card_holder_name": null,
            "expiry_date": "12/29"
        }
    ]
}


// ─────────────────────────────────────────────────────────
// ALTERNATIVE — Saved Card Flow (no redirect needed)
// ─────────────────────────────────────────────────────────

// 1. Create order with payment_type = "saved_card"  (same as STEP 3)
// 2. Driver sends counter offer                       (same as STEP 4)
// 3. User accepts offer:
{
    method: "POST",
        url: "/order/offer/{offer_id}/accept",
            headers: { "Authorization": "Bearer {user_token}" },
    // order.payment_type = "saved_card" → charges default card instantly
    expected_response: {
        "success": true,
            "message": "Offer accepted successfully",
                "data": {
            "status": "driver_accepted"
            // driver assigned immediately — no webhook needed
        }
    }
}


// ─────────────────────────────────────────────────────────
// REAL-TIME CHANNELS TO LISTEN ON (during test)
// ─────────────────────────────────────────────────────────
// Subscribe via Pusher JS SDK or Postman WebSocket:
//
//   trip-{order_id}    → TripStatusUpdated  (searching → assigned)
//                      → offer.status_changed
//                      → chat
//
//   offer-{offer_id}   → OfferUpdated
//                          status: pending → user_accepted → (after webhook) driver assigned
//                          actor_type: 'user' | 'driver'
//
//   user-{user_id}     → offer.sent  (when driver counters)
//
//   driver-{driver_id} → offer.sent  (when user counters back)
