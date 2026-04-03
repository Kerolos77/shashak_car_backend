<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Http\Resources\BrandsResource;

class CarApiController extends Controller
{

    use ResponseHelper;
    public function get_car_brands()
    {
        $brands =  CarBrand::get();
        return $this->apiResponseHandler(200, true, 'succes', BrandsResource::collection($brands));
    }
    public function get_car_models(Request $request)
    {
        $model =  CarModel::where('car_brand_id',$request->brand_id)->get();
        return $this->apiResponseHandler(200, true, 'succes', ModelResource::collection($model));
    }
}
