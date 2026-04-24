<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DemandMapController extends Controller
{
    /**
     * Get the demand map (Hexagon Grid) around the driver's location.
     */
    public function getDemandMap(Request $request)
    {
        $user = $request->user();

        if (!$user->latitude || !$user->longitude) {
            return response()->json([
                'success' => false,
                'message' => 'Driver location is not available. Please update your location first.',
            ], 400);
        }

        $lat0 = (float) $user->latitude;
        $lng0 = (float) $user->longitude;
        $radius = 5.0; // 5 kilometers
        $timeframeMinutes = 15;
        $S = 620; // Hexagon size in meters (~1 sq km area)

        // Query orders created in the last 15 minutes
        $timeLimit = Carbon::now()->subMinutes($timeframeMinutes);

        // Fetch orders and filter by radius using Haversine
        $orders = Order::select('id', 'source_lat', 'source_long')
            ->selectRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(source_lat)) * cos(radians(source_long) - radians(?)) + sin(radians(?)) * sin(radians(source_lat)))) AS distance",
                [$lat0, $lng0, $lat0]
            )
            ->where('created_at', '>=', $timeLimit)
            ->whereNotNull('source_lat')
            ->whereNotNull('source_long')
            ->having('distance', '<=', $radius)
            ->get();

        // --- START DUMMY DATA FOR TESTING ---
        // If there are not enough orders, generate some fake ones around the driver
        $ordersCollection = collect($orders);
        if ($ordersCollection->count() < 5) {
            for ($i = 0; $i < 80; $i++) {
                // Generate random point within 3.5km radius
                $radiusInDegrees = 3.5 / 111.32;
                $u = mt_rand() / mt_getrandmax();
                $v = mt_rand() / mt_getrandmax();
                $w = $radiusInDegrees * sqrt($u);
                $t = 2 * pi() * $v;
                
                $x = $w * cos($t);
                $y = $w * sin($t);
                
                $newLat = $lat0 + $y;
                $newLng = $lng0 + ($x / cos(deg2rad($lat0)));
                
                $ordersCollection->push((object)[
                    'source_lat' => $newLat,
                    'source_long' => $newLng
                ]);
            }
        }
        $orders = $ordersCollection;
        // --- END DUMMY DATA ---

        $hexagons = [];

        foreach ($orders as $order) {
            $lat = (float) $order->source_lat;
            $lng = (float) $order->source_long;

            // 1. Convert to flat plane relative to driver center
            $x = ($lng - $lng0) * 111320 * cos(deg2rad($lat0));
            $y = ($lat - $lat0) * 111320;

            // 2. Map to Hex axial coordinates
            $q = ((sqrt(3)/3) * $x - (1/3) * $y) / $S;
            $r = ((2/3) * $y) / $S;

            // 3. Round to nearest hex
            list($hex_q, $hex_r) = $this->axialRound($q, $r);

            $hexId = "{$hex_q},{$hex_r}";

            if (!isset($hexagons[$hexId])) {
                // Calculate center point of this hexagon to return to client
                $center_x = $S * (sqrt(3) * $hex_q + (sqrt(3)/2) * $hex_r);
                $center_y = $S * (3/2 * $hex_r);
                
                $center_lat = $lat0 + ($center_y / 111320);
                $center_lng = $lng0 + ($center_x / (111320 * cos(deg2rad($lat0))));

                $hexagons[$hexId] = [
                    'id' => $hexId,
                    'center' => [
                        'lat' => $center_lat,
                        'lng' => $center_lng,
                    ],
                    'count' => 0,
                    'intensity' => 'low',
                ];
            }

            $hexagons[$hexId]['count']++;
        }

        // Determine intensity levels
        $hexList = [];
        foreach ($hexagons as $hexId => $hex) {
            $count = $hex['count'];
            if ($count >= 5) {
                $hex['intensity'] = 'high';
            } elseif ($count >= 3) {
                $hex['intensity'] = 'medium';
            } else {
                $hex['intensity'] = 'low';
            }
            $hexList[] = $hex;
        }

        return response()->json([
            'success' => true,
            'message' => 'Demand map retrieved successfully',
            'data' => [
                'timeframe_minutes' => $timeframeMinutes,
                'radius_km' => $radius,
                'hexagons' => $hexList
            ]
        ]);
    }

    /**
     * Round axial coordinates to nearest hex.
     */
    private function axialRound($q, $r)
    {
        $s = -$q - $r;
        $rq = round($q);
        $rr = round($r);
        $rs = round($s);

        $q_diff = abs($rq - $q);
        $r_diff = abs($rr - $r);
        $s_diff = abs($rs - $s);

        if ($q_diff > $r_diff && $q_diff > $s_diff) {
            $rq = -$rr - $rs;
        } else if ($r_diff > $s_diff) {
            $rr = -$rq - $rs;
        }

        return [(int) $rq, (int) $rr];
    }
}
