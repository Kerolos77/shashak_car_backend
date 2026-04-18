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
        $data = $request->all();
        if (empty($data) && $request->getContent()) {
            $data = json_decode($request->getContent(), true) ?? [];
        }

        // Ensure required fields
        if (!isset($data['label']) || !isset($data['address']) || !isset($data['latitude']) || !isset($data['longitude'])) {
            return Resp(null, 'Missing required fields (label, address, latitude, longitude)', 400, false);
        }

        // Handle is_default boolean
        $isDefault = false;
        if (isset($data['is_default'])) {
            $val = $data['is_default'];
            $isDefault = ($val === 'true' || $val === '1' || $val === true || $val === 1);
        }

        if ($isDefault) {
            UserFavoriteLocation::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $location = UserFavoriteLocation::create([
            'user_id' => Auth::id(),
            'label' => $data['label'],
            'address' => $data['address'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
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

        $data = $request->all();
        if (empty($data) && $request->getContent()) {
            $data = json_decode($request->getContent(), true) ?? [];
        }

        // Handle is_default boolean specifically
        if (isset($data['is_default'])) {
            $val = $data['is_default'];
            $data['is_default'] = ($val === 'true' || $val === '1' || $val === true || $val === 1);
            
            if ($data['is_default']) {
                UserFavoriteLocation::where('user_id', Auth::id())->update(['is_default' => false]);
            }
        }

        // Manually update fields to ensure they are picked up correctly
        $allowedFields = ['label', 'address', 'latitude', 'longitude', 'is_default'];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $location->{$field} = $data[$field];
            }
        }

        $location->save();

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
