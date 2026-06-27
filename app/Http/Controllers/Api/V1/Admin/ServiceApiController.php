<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Gate;
use App\Models\Service;
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
        return  ServicesResource::Collection(Service::type(0)->get());
    }
    public function outcity()
    {
        return  ServicesResource::Collection(Service::type(1)->get());
    }
    public function all()
     
    {
        return  ServicesResource::Collection(Service::get());

    }
  public function index()
{
    $services = Service::with('models')->get();

    return Resp($services, 'Services fetched successfully');
}

    public function rides()
    {
        return ServicesResource::Collection(Service::serviceType('ride')->get());
    }

    public function travels()
    {
        return ServicesResource::Collection(Service::serviceType('travel')->get());
    }

    public function shipping()
    {
        return ServicesResource::Collection(Service::serviceType('shipping')->get());
    }


    // public function store(StoreServiceRequest $request)
    // {
    //     $service = Service::create($request->validated());

    //     return (new ServiceResource($service))
    //         ->response()
    //         ->setStatusCode(Response::HTTP_CREATED);
    // }

    // public function show(Service $service)
    // {
    //     abort_if(Gate::denies('service_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return new ServiceResource($service);
    // }

    // public function update(UpdateServiceRequest $request, Service $service)
    // {
    //     $service->update($request->validated());

    //     return (new ServiceResource($service))
    //         ->response()
    //         ->setStatusCode(Response::HTTP_ACCEPTED);
    // }

    // public function destroy(Service $service)
    // {
    //     abort_if(Gate::denies('service_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     $service->delete();

    //     return response(null, Response::HTTP_NO_CONTENT);
    // }
}
