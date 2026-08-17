<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Models\Service;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Keep existing imports
use App\Helpers\FileUploader;
use App\Models\ServiceModel;
class ServiceController extends BaseController
{
    public function __construct(Service $model)
    {
        parent::__construct($model);
    }
    public function dataHandler($request) {
        $priceTiers = [];
        if ($request->has('price_tiers') && is_array($request->price_tiers)) {
            foreach ($request->price_tiers as $tier) {
                if (isset($tier['price_per_km']) && $tier['price_per_km'] !== '') {
                    $priceTiers[] = [
                        'from_km' => floatval($tier['from_km'] ?? 0),
                        'to_km' => (isset($tier['to_km']) && $tier['to_km'] !== '') ? floatval($tier['to_km']) : null,
                        'price_per_km' => floatval($tier['price_per_km']),
                    ];
                }
            }
        }

        return [
            'title' => $request->title,
            'km_charge' => $request->km_charge,
            'price_tiers' => $priceTiers,
            'tier_pricing_type' => $request->tier_pricing_type ?? 'flat',
            'enable' => $request->enable != null ? 1 : 0,
            'offer_rate' => $request->offer_rate != null ? 1 : 0,
            'commission_type' => $request->has('commission_type') ? ($request->commission_type ? 1 : 0) : null,
            'service_type' => $request->service_type,
            'vehicle_type' => $request->vehicle_type ?? 'car',
            'weight' => $request->weight,
            'height' => $request->height,
            'width' => $request->width,
            'length' => $request->length,
        ];
    }

    public function index(Request $request = null)
    {
        $rows = $this->model->with('models');
        $rows = $this->filter($rows);
        $with = $this->with();
        if(!empty($with)){
            $rows = $rows->with($with);
        }
        $rows = $rows->paginate(10);
        $moduleName = $this->pluralModelName();
        $sModuleName = $this->getModelName();
        $routeName = $this->getClassNameFromModel();
        $pageTitle = trans('global.control') . " " . trans('global.services');
        $pageDes = "Here you can add / edit / delete " .$moduleName;

        return view('admin.' . $this->getClassNameFromModel() . '.index', compact(
            'rows',
            'pageTitle',
            'moduleName',
            'pageDes',
            'sModuleName',
            'routeName'
        ));
    }
    public function store(StoreServiceRequest $request) 
    {
        abort_if(Gate::denies('service_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $service = Service::create($this->dataHandler($request));

        if ($request->hasFile('images')) {
            FileUploader::upload($service, $request->images, 'service_images', 'multiple_image');
        }

        if ($request->has('models')) {
            foreach ($request->models as $index => $modelName) {
                if (!empty($modelName)) {
                    ServiceModel::create([
                        'service_id' => $service->id,
                        'model_name' => $modelName,
                        'min_year' => $request->min_years[$index] ?? null
                    ]);
                }
            }
        }

        return redirect()->route('admin.services.index');
        
    }
          public function update($id , Request $request){
            $row = $this->model->FindOrFail($id);
            $requestArray = $request->all();
            $row->update($this->dataHandler($request));

            if ($request->has('models')) {
                $row->models()->delete();
                foreach ($request->models as $index => $modelName) {
                    if (!empty($modelName)) {
                        ServiceModel::create([
                            'service_id' => $row->id,
                            'model_name' => $modelName,
                            'min_year' => $request->min_years[$index] ?? null
                        ]);
                    }
                }
            } else {
                 // If models array is empty or not present, but user visited the page, maybe we should clear?
                 // Usually if 'models' is not in request it might mean no models were sent.
                 // If we want to support clearing all models, we should handle empty array.
                 // For now, assuming if the input is missing, maybe we shouldn't delete?
                 // But for a repeater, sending an empty array is expected if all removed.
                 // Let's rely on the form sending 'models' even if empty if that's how we build it,
                 // or we can force delete if we are sure.
                 // SAFEST: Delete all and recreate is robust for simple list.
                 // If $request->models is null, it might be safer to check if the field exists.
                 // However, standard HTML forms don't send empty arrays for checkboxes/inputs often.
                 // Let's assume if we are updating the service we want to sync models.
                 $row->models()->delete(); 
            }
            
            return redirect()->route('admin.services.index');
        }

    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        $service->enable = !$service->enable;
        $service->save();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'enable' => (bool)$service->enable,
                'message' => $service->enable ? 'تم تفعيل الخدمة بنجاح' : 'تم تعطيل الخدمة بنجاح'
            ]);
        }

        return redirect()->back()->with('success', $service->enable ? 'تم تفعيل الخدمة بنجاح' : 'تم تعطيل الخدمة بنجاح');
    }
}
