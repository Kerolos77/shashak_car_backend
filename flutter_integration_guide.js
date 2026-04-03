/**
 * ============================================================
 *  SHAKSHAK — Flutter Developer Integration Guide
 *  Payment + Real-Time Cycle
 *  Base URL: http://shakshak.net/api/v1
 * ============================================================
 *
 *  REQUIRED PACKAGES (pubspec.yaml):
 *    - dio / http           → API calls
 *    - pusher_channels_flutter → real-time
 *    - flutter_paymob / webview_flutter → payment page
 * ============================================================
 */


// ══════════════════════════════════════════════════════════
//  PART 1 — CREATE ORDER
// ══════════════════════════════════════════════════════════

/*
  POST /order/new
  Headers: Authorization: Bearer {token}

  Body:
  {
    "service_id":          1,
    "source_address":      "...",
    "source_lat":          "30.0444",
    "source_long":         "31.2357",
    "destination_address": "...",
    "destination_lat":     "30.1127",
    "destination_long":    "31.4000",
    "offer_rate":          "150",        ← السعر الابتدائي
    "distance":            "18",
    "inter_city":          0,
    "is_female_only":      false,
    "payment_type":        "card"        ← "cash" | "wallet" | "card" | "saved_card"
  }

  SUCCESS Response:
  {
    "data": {
      "id": 889,            ← احفظ order_id
      "status": "pending",
      "payment_type": "card"
    }
  }

  ✅ بعده: اشترك على channel → trip-889
  ✅ راقب: TripStatusUpdated event
*/


// ══════════════════════════════════════════════════════════
//  PART 2 — REAL-TIME SETUP (Pusher)
// ══════════════════════════════════════════════════════════

/*
  Pusher Config:
  {
    "key":    "key",                  ← من .env PUSHER_APP_KEY
    "host":   "shakshak.net",        ← production host
    "port":   6001,
    "scheme": "http",
    "cluster": "mt1"
  }

  CHANNELS TO SUBSCRIBE:

  1. trip-{order_id}
     Event: .TripStatusUpdated
     Payload: { id, status, driver_id, payment_status, driver: {...} }
     When: order status changes (searching→assigned→arrived→on_trip→completed)

  2. offer-{offer_id}
     Event: .OfferUpdated
     Payload: { offer_id, status, actor_type, actor_id, offer_rate }
     Statuses: pending → countered → user_accepted → [payment] → assigned
     When: each time offer status changes

  3. user-{user_id}
     Event: .offer.sent
     When: driver sends a new offer to the user

  ⚠️ Subscribe on trip channel IMMEDIATELY after creating order
  ⚠️ Subscribe on offer channel AFTER receiving first offer
*/


// ══════════════════════════════════════════════════════════
//  PART 3 — OFFER NEGOTIATION
// ══════════════════════════════════════════════════════════

/*
  A) Driver sends offer → user receives via user-{user_id} channel
     Payload includes: offer_id, driver info, offer_rate

  B) User accepts offer:
     POST /order/offer/{offer_id}/accept
     Headers: Authorization: Bearer {token}
     Body: {}

     RESPONSE depends on payment_type:

     ─── CASH / WALLET ────────────────────────────────────
     HTTP 200: { "data": { "status": "driver_accepted" } }
     → Driver assigned immediately → go to Tracking Screen

     ─── CARD (new card) ──────────────────────────────────
     HTTP 402: {
       "data": {
         "payment_required": true,
         "amount": 180,
         "order_id": 889,
         "offer_id": 3
       }
     }
     → Open Payment Flow (see PART 4)

     ─── SAVED CARD ───────────────────────────────────────
     HTTP 200: { "data": { "status": "driver_accepted" } }
     → Charged instantly → Driver assigned → go to Tracking Screen

  C) User counter offer (optional):
     POST /order/offer/user-counter
     Body: { "offer_id": 3, "counter_offer": "160" }

  D) Driver accepts user's price directly:
     POST /order/offer/driver-accept-user-price
     Body: { "order_id": 889 }
     → Same payment logic applies
*/


// ══════════════════════════════════════════════════════════
//  PART 4 — CARD PAYMENT FLOW (New Card)
// ══════════════════════════════════════════════════════════

/*
  STEP 4.1 — Create Payment Intention
  POST /paymob/payment-intention
  Headers: Authorization: Bearer {token}
  Body: {
    "order_id":  889,
    "amount":    180,
    "save_card": true    ← show "Save Card" checkbox to user
  }

  Response: {
    "data": {
      "checkout_url":  "https://accept.paymob.com/unifiedcheckout/?publicKey=...&clientSecret=...",
      "client_secret": "...",
      "public_key":    "egy_pk_test_..."
    }
  }

  STEP 4.2 — Open Payment Page
  → Open checkout_url in WebView or in-app browser
  → User enters card details + optional "Save Card" tick
  → User submits → Paymob processes

  STEP 4.3 — Wait for Real-Time Confirmation
  → Listen on trip-{order_id} channel
  → When TripStatusUpdated fires with status = "assigned"
     → Driver was assigned → Close WebView → Go to Tracking Screen

  ⚠️ DO NOT rely on WebView redirect URL for success detection
  ⚠️ Always use real-time event as the source of truth
*/


// ══════════════════════════════════════════════════════════
//  PART 5 — SAVED CARDS
// ══════════════════════════════════════════════════════════

/*
  LIST saved cards:
  GET /paymob/saved-cards
  Headers: Authorization: Bearer {token}
  Response: [
    {
      "id":           1,
      "card_subtype": "VISA",
      "masked_pan":   "**** 1234",
      "is_default":   true,
      "expiry_month": "12",
      "expiry_year":  "2029"
    }
  ]

  SET default card:
  PUT /paymob/saved-cards/{id}/default

  DELETE card:
  DELETE /paymob/saved-cards/{id}

  PAY with saved card (used automatically when payment_type="saved_card"):
  POST /paymob/pay-with-saved-card
  Body: { "saved_card_id": 1, "amount": 180, "order_id": 889 }
*/


// ══════════════════════════════════════════════════════════
//  PART 6 — COMPLETE FLOW DIAGRAM
// ══════════════════════════════════════════════════════════

/*
  [User] Create Order (payment_type=card)
       ↓
  Subscribe: trip-{order_id}, user-{user_id}
       ↓
  [Driver] Sends offer → user-channel fires
       ↓
  Subscribe: offer-{offer_id}
       ↓
  [User] Accept Offer → POST /offer/{id}/accept
       ↓
       ├── CASH/WALLET/SAVED_CARD → HTTP 200 → Tracking Screen ✅
       └── CARD → HTTP 402 (payment_required: true)
                      ↓
               POST /paymob/payment-intention
                      ↓
               Open checkout_url in WebView
                      ↓
               User pays / saves card
                      ↓
               Paymob webhook fires (server-side)
                      ↓
               trip-{order_id} → TripStatusUpdated (status=assigned)
                      ↓
               Close WebView → Tracking Screen ✅


  TRACKING SCREEN statuses (listen on trip-{order_id}):
  assigned → arrived → on_trip → completed
*/


// ══════════════════════════════════════════════════════════
//  PART 7 — WHAT'S MISSING / TODO FOR FLUTTER
// ══════════════════════════════════════════════════════════

/*
  [ ] Show saved cards list on payment selection screen
  [ ] "Save this card" checkbox inside WebView flow
  [ ] Handle HTTP 402 response after acceptOffer → open Paymob WebView
  [ ] Listen on trip-{order_id} for TripStatusUpdated to close WebView
  [ ] Handle saved_card payment_type (no WebView needed)
  [ ] Show wallet integration if user chooses Vodafone Cash etc.
      (same checkout_url — Paymob shows both card + wallet options)
  [ ] Manage cards screen: list, set default, delete
  [ ] Handle payment failure gracefully (let user retry)
  [ ] On TripStatusUpdated → payment_status = "paid" → show confirmation
*/
