/**
 * =========================================================
 *  SHAKSHAK â€” Real-Time Pusher Events Reference
 *  Host : ws://shakshak.net:6001
 *  All channels are PUBLIC unless marked [PRIVATE]
 * =========================================================
 *
 *  HOW TO SUBSCRIBE (Pusher raw message):
 *    { "event": "pusher:subscribe", "data": { "channel": "CHANNEL_NAME" } }
 *
 *  HOW TO UNSUBSCRIBE:
 *    { "event": "pusher:unsubscribe", "data": { "channel": "CHANNEL_NAME" } }
 *
 *  PRIVATE channels need an auth token first via POST /api/v1/broadcasting/auth
 * =========================================================
 */


// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// 1. TRIP CHANNEL
//    Channel  : trip-{order_id}
//    Type     : PUBLIC
//    Listen   : all parties (user + driver) on the same trip
//    Events   : TripStatusUpdated | offer.status_changed | chat
// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

// â†’ SUBSCRIBE
{ "event": "pusher:subscribe", "data": { "channel": "trip-889" } }

// â”€â”€â”€ Event 1: TripStatusUpdated â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Triggered by: neworder / accept / arrived / start / end / cancel
// Status values:
//   searching   â†’ order created, waiting for driver
//   negotiating â†’ driver sent counter-offer
//   assigned    â†’ driver accepted / offer accepted
//   arrived     â†’ driver reached pickup location
//   on_trip     â†’ trip started
//   completed   â†’ trip ended
//   canceled    â†’ order canceled (refunds wallet if payment_type=wallet)
{
    "event": "trip-889",
        "channel": "trip-889",
            "data": {
        "status": "assigned",
            "order": {
            "id": 889,
                "status": "assigned",
                    "source_address": "Tahrir Square, Cairo",
                        "destination_address": "Cairo Airport",
                            "offer_rate": "150",
                                "final_rate": "150",
                                    "distance": "18.5",
                                        "inter_city": 0,
                                            "is_female_only": false,
                                                "driver_id": 5,
                                                    "driver_name": "Mohamed Hassan",
                                                        "driver_phone": "+201001234567",
                                                            "car_color": "White",
                                                                "car_brand": "Toyota",
                                                                    "car_model": "Corolla",
                                                                        "car_number": "ABC 123"
        }
    }
}

// â”€â”€â”€ Event 2: offer.status_changed â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Triggered by: acceptOffer / denyOffer / driverAcceptUserPrice
// actor_type â†’ who did the action: 'user' | 'driver'
{
    "event": ".offer.status_changed",
        "channel": "trip-889",
            "data": {
        "offer_id": 3,
            "order_id": 889,
                "driver_id": 5,
                    "user_id": 2,
                        "status": "accepted",          // accepted | denied
                            "action": "accepted",
                                "actor_type": "user",          // â† Ù…ÙŠÙ† Ù‚Ø¨Ù„ / Ø±ÙØ¶
                                    "actor_id": 2,
                                        "offer_rate": "180.000",
                                            "user_counter_offer": null,
                                                "driver": {
            "id": 5,
                "name": "Mohamed Hassan",
                    "phone_number": "+201001234567"
        }
    }
}

// â”€â”€â”€ Event 3: chat â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Triggered by: GET /api/v1/send/chat?trip_id=889&message=...
{
    "event": ".chat",
        "channel": "trip-889",
            "data": {
        "id": 12,
            "trip_id": 889,
                "sender_id": 2,
                    "message": "Hello! I'm on my way",
                        "created_at": "2026-03-03T22:00:00.000Z"
    }
}


// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// 2. OFFER CHANNEL
//    Channel  : offer-{offer_id}
//    Type     : PUBLIC
//    Listen   : both user & driver on a specific offer
//    Event    : OfferUpdated
//    Key info : actor_type + actor_id Ø­Ø§Ø¶Ø±ÙŠÙ† Ø¯Ø§ÙŠÙ…Ø§Ù‹
// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

// â†’ SUBSCRIBE
{ "event": "pusher:subscribe", "data": { "channel": "offer-3" } }

// â”€â”€â”€ Event: OfferUpdated â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Triggered by:
//   POST /order/offer/driver-counter         â†’ status: pending    | actor: driver
//   POST /order/offer/driver-accept-user-price â†’ status: accepted | actor: driver
//   POST /order/offer/{id}/accept            â†’ status: accepted    | actor: user|driver
//   POST /order/offer/{id}/deny              â†’ status: denied      | actor: user|driver
//
// Offer status flow:
//
//   pending â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â†’ countered
//                                        â”‚
//                            â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
//                            â†“           â†“
//                     user_accepted   driver_accepted
//
//   pending â”€â”€â†’ driver_accepted   (driver accepts user's original price)
//
//   pending/countered â†’ user_denied      (user refuses)
//   pending/countered â†’ driver_canceled  (driver withdraws)
//
// who did the action â†’ always in actor_type + actor_id
{
    "event": ".OfferUpdated",
        "channel": "offer-3",
            "data": {
        "offer_id": 3,
            "order_id": 889,
                "status": "user_accepted",   // pending | countered | user_accepted | driver_accepted | user_denied | driver_canceled
                    "actor_type": "user",          // â† Ù…ÙŠÙ† Ø¹Ù…Ù„ Ø§Ù„Ù€ action
                        "actor_id": 2,
                            "sender_type": "driver",       // â† Ù…ÙŠÙ† Ø¨Ø¹Øª Ø§Ù„Ø¹Ø±Ø¶ Ø§Ù„Ø£ÙˆÙ„
                                "offer_rate": "180.000",
                                    "user_counter_offer": null,
                                        "car_color": "White",
                                            "car_number": "ABC 123",
                                                "car_brand": "Toyota",
                                                    "car_model": "Corolla",
                                                        "driver": {
            "id": 5,
                "name": "Mohamed Hassan",
                    "phone_number": "+201001234567",
                        "profile_pic": "https://shakshak.net/uploads/drivers/5.jpg"
        },
        "order": {
            "id": 889,
                "source_address": "Tahrir Square",
                    "destination_address": "Cairo Airport",
                        "status": "assigned"
        },
        "updated_at": "2026-03-03T22:00:00.000Z"
    }
}


// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// 3. USER CHANNEL
//    Channel  : user-{user_id}
//    Type     : PUBLIC
//    Listen   : the customer / passenger
//    Events   : offer.sent | payment.status_updated
// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

// â†’ SUBSCRIBE
{ "event": "pusher:subscribe", "data": { "channel": "user-2" } }

// â”€â”€â”€ Event 1: offer.sent â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Triggered by: POST /order/offer/driver-counter
// = Ø¥Ø´Ø¹Ø§Ø± Ù„Ù„Ù…Ø³ØªØ®Ø¯Ù… Ø¥Ù† Ø§Ù„Ø³Ø§Ø¦Ù‚ Ø¨Ø¹ØªÙ„Ù‡ Ø¹Ø±Ø¶ Ø¬Ø¯ÙŠØ¯
{
    "event": ".offer.sent",
        "channel": "user-2",
            "data": {
        "offer_id": 3,
            "order_id": 889,
                "driver_id": 5,
                    "user_id": 2,
                        "sender_type": "driver",
                            "offer_rate": "180.000",
                                "user_counter_offer": null,
                                    "status": "pending",
                                        "car_color": "White",
                                            "car_number": "ABC 123",
                                                "car_brand": "Toyota",
                                                    "car_model": "Corolla",
                                                        "driver": {
            "id": 5,
                "name": "Mohamed Hassan",
                    "phone_number": "+201001234567",
                        "profile_pic": "https://shakshak.net/uploads/drivers/5.jpg"
        },
        "order": {
            "id": 889,
                "source_address": "Tahrir Square",
                    "destination_address": "Cairo Airport"
        },
        "created_at": "2026-03-03T22:00:00.000Z"
    }
}

// â”€â”€â”€ Event 2: payment.status_updated â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Triggered by: POST /api/v1/paymob/webhook (Paymob callback)
{
    "event": ".payment.status_updated",
        "channel": "user-2",
            "data": {
        "transaction_id": 10,
            "payment_id": "paymob_ref_123",
                "amount": 15000,
                    "status": "success",
                        "success": true,
                            "payment_method": "card",
                                "created_at": "2026-03-03T22:00:00+00:00",
                                    "updated_at": "2026-03-03T22:01:00+00:00"
    }
}


// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// 4. DRIVER CHANNELS
//    Channel A: driver-{driver_id}       â†’ PUBLIC
//    Channel B: private-driver.{id}      â†’ PRIVATE [needs auth]
//    Channel C: drivers                  â†’ PUBLIC
// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

// â†’ SUBSCRIBE (public)
{ "event": "pusher:subscribe", "data": { "channel": "driver-5" } }

// â†’ SUBSCRIBE (private â€” eligible drivers only, needs auth header)
{ "event": "pusher:subscribe", "data": { "channel": "private-driver.5", "auth": "APP_KEY:SIGNATURE" } }

// â†’ SUBSCRIBE (all drivers â€” broadcast)
{ "event": "pusher:subscribe", "data": { "channel": "drivers" } }

// â”€â”€â”€ Event (driver-{id}): offer.sent â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Triggered by: user sending a counter-offer back to driver
{
    "event": ".offer.sent",
        "channel": "driver-5",
            "data": {
        "offer_id": 3,
            "order_id": 889,
                "sender_type": "user",
                    "offer_rate": "150.000",
                        "user_counter_offer": "160.000",
                            "status": "countered",
                                "order": {
            "id": 889,
                "source_address": "Tahrir Square",
                    "destination_address": "Cairo Airport"
        },
        "created_at": "2026-03-03T22:00:00.000Z"
    }
}

// â”€â”€â”€ Event (private-driver.{id} + drivers): drivers1 â”€â”€â”€â”€â”€â”€â”€â”€
// Triggered by: POST /order/new  â†’ new order broadcast to eligible drivers
{
    "event": ".drivers1",
        "channel": "private-driver.5",   // or "drivers" for the public broadcast
            "data": {
        "order": {
            "id": 889,
                "status": "searching",
                    "source_address": "Tahrir Square, Cairo",
                        "destination_address": "Cairo Airport",
                            "offer_rate": "150",
                                "inter_city": 0,
                                    "is_female_only": false,
                                        "service_id": 1
        },
        "eligible_driver_ids": [5, 8, 12]
    }
}


// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// 5. LOCATION CHANNEL  [PRIVATE]
//    Channel : private-location.{user_id}
//    Type    : PRIVATE (requires auth)
//    Listen  : track a specific user/driver location live
//    Event   : LocationUpdated
// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

// â†’ SUBSCRIBE (needs auth)
{ "event": "pusher:subscribe", "data": { "channel": "private-location.5", "auth": "APP_KEY:SIGNATURE" } }

// â”€â”€â”€ Event: LocationUpdated â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Triggered by: POST /api/v1/location/update
{
    "event": ".LocationUpdated",
        "channel": "private-location.5",
            "data": {
        "user_id": 5,
            "latitude": 30.0444,
                "longitude": 31.2357,
                    "updated_at": "2026-03-03 22:00:00"
    }
}
