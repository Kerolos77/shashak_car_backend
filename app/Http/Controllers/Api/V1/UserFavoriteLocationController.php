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
        // $request->all() works for both JSON and Form-Data in Laravel
        $data = $request->all();

        // Ensure required fields
        if (!$request->filled(['label', 'address', 'latitude', 'longitude'])) {
            return Resp(null, 'Missing required fields (label, address, latitude, longitude)', 400, false);
        }

        // Handle is_default boolean
        $isDefault = false;
        if ($request->has('is_default')) {
            $val = $request->input('is_default');
            $isDefault = ($val === 'true' || $val === '1' || $val === true || $val === 1);
        }

        if ($isDefault) {
            UserFavoriteLocation::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $location = UserFavoriteLocation::create([
            'user_id' => Auth::id(),
            'label' => $request->input('label'),
            'address' => $request->input('address'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
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

        // Handle is_default boolean specifically
        if ($request->has('is_default')) {
            $val = $request->input('is_default');
            $data['is_default'] = ($val === 'true' || $val === '1' || $val === true || $val === 1);
            
            if ($data['is_default']) {
                UserFavoriteLocation::where('user_id', Auth::id())->update(['is_default' => false]);
            }
        }

        // Whitelist allowed fields from the request
        $allowedFields = ['label', 'address', 'latitude', 'longitude', 'is_default'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));

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
