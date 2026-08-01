<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Gate;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServicesResource;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\Admin\ServiceResource;

class ServiceApiController extends Controller
{
    public function incity()
    {
        $setting = Setting::first();
        if ($setting && isset($setting->ride_enabled) && !$setting->ride_enabled) {
            return ServicesResource::Collection(collect());
        }
        return ServicesResource::Collection(Service::type(0)->where('enable', true)->get());
    }

    public function outcity()
    {
        $setting = Setting::first();
        if ($setting && isset($setting->intercity_enabled) && !$setting->intercity_enabled) {
            return ServicesResource::Collection(collect());
        }
        return ServicesResource::Collection(Service::type(1)->where('enable', true)->get());
    }

    public function all()
    {
        return ServicesResource::Collection(Service::where('enable', true)->get());
    }

    public function index()
    {
        $setting = Setting::first();
        $query = Service::with('models')->where('enable', true);

        if ($setting && isset($setting->shipping_enabled) && !$setting->shipping_enabled) {
            $query->where('service_type', '!=', 'shipping');
        }
        if ($setting && isset($setting->ride_enabled) && !$setting->ride_enabled) {
            $query->where('service_type', '!=', 'ride');
        }
        if ($setting && isset($setting->travel_enabled) && !$setting->travel_enabled) {
            $query->where('service_type', '!=', 'travel');
        }

        $services = $query->get();

        return Resp($services, 'Services fetched successfully');
    }

    public function rides()
    {
        $setting = Setting::first();
        if ($setting && isset($setting->ride_enabled) && !$setting->ride_enabled) {
            return ServicesResource::Collection(collect());
        }
        return ServicesResource::Collection(Service::where('enable', true)->serviceType('ride')->get());
    }

    public function travels()
    {
        $setting = Setting::first();
        if ($setting && isset($setting->travel_enabled) && !$setting->travel_enabled) {
            return ServicesResource::Collection(collect());
        }
        return ServicesResource::Collection(Service::where('enable', true)->serviceType('travel')->get());
    }

    public function shipping()
    {
        $setting = Setting::first();
        if ($setting && isset($setting->shipping_enabled) && !$setting->shipping_enabled) {
            return ServicesResource::Collection(collect());
        }
        return ServicesResource::Collection(Service::where('enable', true)->serviceType('shipping')->get());
    }
}
