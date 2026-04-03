<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marketopia\MarketopiaCity;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request) 
    {
        $searchQuery = $request->input('search');

        $cities = MarketopiaCity::with('country', 'state')->whereHas('city_translations', function ($query) use ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%');
        })->orderBy('id', 'asc')->paginate(10);
        $pageTitle = __('app.cities');
        return view('admin.cities.index', compact('cities', 'pageTitle'));
    }
    public function deactivate($id)
{
    $country = MarketopiaCity::findOrFail($id);
    $country->status = 0;
    $country->save();

    return redirect()->route('admin.cities.index');
}

public function activate($id)
{
    $country = MarketopiaCity::findOrFail($id);
    $country->status = 1;
    $country->save();

    return redirect()->route('admin.cities.index');
}
}
