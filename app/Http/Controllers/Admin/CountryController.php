<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marketopia\MarketopiaCountry;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');

        $countries = MarketopiaCountry::with('continent', 'sub_continent')->whereHas('country_translations', function ($query) use ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%');
        })->orderBy('id', 'asc')->paginate(10);
        $pageTitle = __('app.countries');
        return view('admin.countries.index', compact('countries', 'pageTitle'));
    }
    public function deactivate($id)
    {
        $country = MarketopiaCountry::findOrFail($id);
        $country->status = 0;
        $country->save();

        return redirect()->route('admin.countries.index');
    }

    public function activate($id)
    {
        $country = MarketopiaCountry::findOrFail($id);
        $country->status = 1;
        $country->save();

        return redirect()->route('admin.countries.index');
    }
    // Create Distory Method
}
