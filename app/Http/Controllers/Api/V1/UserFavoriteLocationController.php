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
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return Resp(null, $validator->errors()->first(), 400, false);
        }

        // If is_default is true, unset other defaults for this user
        if ($request->is_default) {
            UserFavoriteLocation::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $location = UserFavoriteLocation::create([
            'user_id' => Auth::id(),
            'label' => $request->label,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_default' => $request->is_default ?? false,
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

        $validator = Validator::make($request->all(), [
            'label' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'latitude' => 'sometimes|string',
            'longitude' => 'sometimes|string',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return Resp(null, $validator->errors()->first(), 400, false);
        }

        if ($request->has('is_default') && $request->is_default) {
            UserFavoriteLocation::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        // We use $request->all() filtered by what we want to update to ensure compatibility with different request types
        $data = $request->only(['label', 'address', 'latitude', 'longitude', 'is_default']);
        $location->fill($data);
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
