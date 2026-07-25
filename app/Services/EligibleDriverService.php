<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceModel;
use App\Models\DriverProfile;

class EligibleDriverService
{
    /**
     * Find eligible drivers for a given order based on:
     * 1. Driver's service_id matches order's service_id
     * 2. Driver's car model matches one of the service's allowed models
     * 3. Driver's car year >= service model's min_year
     * 4. Driver is active and online
     * 5. Female-only orders only go to female drivers
     *
     * @param Order $order
     * @return \Illuminate\Support\Collection
     */
    public static function getEligibleDrivers(Order $order)
    {
        $serviceId = $order->service_id;
        $isFemaleOnly = $order->is_female_only;

        // Get the service and its allowed models
        $service = Service::with('models')->find($serviceId);
        
        if (!$service) {
            return collect([]);
        }

        // Get allowed model names and their min_years
        $allowedModels = $service->models->pluck('min_year', 'model_name')->toArray();

        // Find all drivers with matching service_id
        $driversQuery = User::drivers()
            ->where('is_active', 1)
            ->where('is_online', 1)
            ->with(['profile.driver_cars.model', 'profile.driver_cars']);

        // Apply female-only filter if required
        if ($isFemaleOnly) {
            $driversQuery->where('gender', 'female');
        }

        $settings = \App\Models\Setting::first();
        $destTolerance = $settings->destination_mode_tolerance_km ?? 5.0;
        $maxPickupDistance = ($order->payment_type === 'cash') 
            ? ($settings->max_cash_pickup_distance_km ?? 10.0) 
            : ($settings->max_card_pickup_distance_km ?? 15.0);

        // Apply Shadow Ban / Cash restriction filter
        if ($order->payment_type === 'cash') {
            $driversQuery->where(function($query) {
                $query->where('cash_restriction_seconds_remaining', '<=', 0)
                      ->orWhereNull('cash_restriction_seconds_remaining');
            });

            if ($settings && $settings->auto_cash_ban_enabled) {
                // Cash debt limit check
                if ($settings->max_driver_cash_debt_limit > 0) {
                    $maxDebt = $settings->max_driver_cash_debt_limit;
                    $driversQuery->where(function($q) use ($maxDebt) {
                        $q->whereNull('balance')
                          ->orWhere('balance', '>=', -$maxDebt);
                    });
                }
            }
        }

        $drivers = $driversQuery->get();

        // Filter drivers based on car model, year requirements, rating, pickup distance, and destination
        $eligibleDrivers = $drivers->filter(function ($driver) use ($allowedModels, $order, $settings, $destTolerance, $maxPickupDistance) {
            // Min rating check for cash orders
            if ($order->payment_type === 'cash' && $settings && $settings->auto_cash_ban_enabled && $settings->min_driver_rating_for_cash > 0) {
                $driverRating = $driver->rating ?? 5.0;
                if ($driverRating < $settings->min_driver_rating_for_cash) {
                    return false;
                }
            }

            // Pickup distance filter
            if ($driver->latitude && $driver->longitude && $order->source_lat && $order->source_long) {
                $pickupDistance = self::calculateDistance(
                    $driver->latitude,
                    $driver->longitude,
                    $order->source_lat,
                    $order->source_long
                );
                if ($pickupDistance > $maxPickupDistance) {
                    return false;
                }
            }

            // Destination Filter Logic
            if ($driver->profile && $driver->profile->is_heading_destination) {
                $destLat = $driver->profile->destination_lat;
                $destLong = $driver->profile->destination_long;

                if ($destLat && $destLong) {
                    $orderDestLat = $order->destination_lat;
                    $orderDestLong = $order->destination_long;

                    // Haversine distance
                    $distance = self::calculateDistance($destLat, $destLong, $orderDestLat, $orderDestLong);
                    if ($distance > $destTolerance) {
                        return false; 
                    }
                }
            }
            // If no models defined for service, all drivers with matching service are eligible
            if (empty($allowedModels)) {
                return true;
            }

            $driverCar = $driver->profile?->driver_cars;
            
            if (!$driverCar) {
                return false;
            }

            $carModelName = $driverCar->model?->title ?? null;
            $carYear = $driverCar->release_year ?? 0;

            // Check if driver's car model is in allowed models
            if (!$carModelName || !isset($allowedModels[$carModelName])) {
                return false;
            }

            $minYear = $allowedModels[$carModelName];

            // Check if car year meets minimum requirement
            if ($minYear && $carYear < $minYear) {
                return false;
            }

            return true;
        });

        return $eligibleDrivers;
    }

    /**
     * Get driver IDs eligible for an order
     *
     * @param Order $order
     * @return array
     */
    public static function getEligibleDriverIds(Order $order): array
    {
        return self::getEligibleDrivers($order)->pluck('id')->toArray();
    }

    private static function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            return ($miles * 1.609344); // return KM
        }
    }
}
