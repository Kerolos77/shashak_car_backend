<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserFavoriteLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserFavoriteLocationController extends Controller
{
    /**
     * Display a listing of the user's favorite locations.
     */
    public function index()
    {
        $locations = UserFavoriteLocation::where('user_id', Auth::id())->get();
        return Resp($locations, 'Favorite locations retrieved successfully');
    }

    /**
     * Store a newly created favorite location in storage.
     */
    public function store(Request $request)
    {
        $label = $request->input('label');
        $address = $request->input('address');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $isDefault = $request->input('is_default');

        if (!$label || !$address || !$latitude || !$longitude) {
            return Resp(null, 'Missing required fields (label, address, latitude, longitude)', 400, false);
        }

        // Handle string-based booleans
        if ($isDefault === 'true' || $isDefault === '1' || $isDefault === 1 || $isDefault === true) {
            $isDefault = true;
        } else {
            $isDefault = false;
        }

        // If is_default is true, unset other defaults for this user
        if ($isDefault) {
            UserFavoriteLocation::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $location = UserFavoriteLocation::create([
            'user_id' => Auth::id(),
            'label' => $label,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'is_default' => $isDefault,
        ]);

        return Resp($location, 'Favorite location saved successfully');
    }

    /**
     * Update the specified favorite location in storage.
     */
    public function update(Request $request, $id)
    {
        $location = UserFavoriteLocation::where('user_id', Auth::id())->find($id);

        if (!$location) {
            return Resp(null, 'Favorite location not found', 404, false);
        }

        // Explicitly extract fields to handle various request types
        $label = $request->input('label');
        $address = $request->input('address');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $isDefault = $request->input('is_default');

        // Handle string-based booleans from mobile apps
        if ($isDefault === 'true' || $isDefault === '1' || $isDefault === 1 || $isDefault === true) {
            $isDefault = true;
        } elseif ($isDefault === 'false' || $isDefault === '0' || $isDefault === 0 || $isDefault === false) {
            $isDefault = false;
        } else {
            $isDefault = null; // No change
        }

        if ($isDefault === true) {
            UserFavoriteLocation::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $updateData = [];
        if (!is_null($label)) $updateData['label'] = $label;
        if (!is_null($address)) $updateData['address'] = $address;
        if (!is_null($latitude)) $updateData['latitude'] = $latitude;
        if (!is_null($longitude)) $updateData['longitude'] = $longitude;
        if (!is_null($isDefault)) $updateData['is_default'] = $isDefault;

        if (!empty($updateData)) {
            $location->update($updateData);
        }

        return Resp($location->fresh(), 'Favorite location updated successfully');
    }

    /**
     * Remove the specified favorite location from storage.
     */
    public function destroy($id)
    {
        $location = UserFavoriteLocation::where('user_id', Auth::id())->find($id);

        if (!$location) {
            return Resp(null, 'Favorite location not found', 404, false);
        }

        $location->delete();

        return Resp(null, 'Favorite location deleted successfully');
    }
}
