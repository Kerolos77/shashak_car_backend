<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverProfile;
use Illuminate\Http\Request;

class DriverController extends BaseController
{
    public function __construct(DriverProfile $model)
    {
        parent::__construct($model);
    }

    public function index()  
    {
        $request = request();
        $allDriversCount = $this->model->count(); 
        $pendingDriversCount = $this->model->where('status', '=', 'pending')->count(); 
        $activeDriversCount = $this->model->where('status', '=', 'active')->count(); 
        $blockedDriversCount = $this->model->where('status', '=', 'blocked')->count(); 
        $rows = DriverProfile::with('user'); 
       if ($request->status != null && $request->status != '') {
              $rows = $rows->where('status', $request->status);
       }
         $rows = $rows->orderBy('id', 'desc')->paginate(10);
         $moduleName = $this->getModelName();
         $pageTitle = $moduleName;
         $pageDes = "Here you can create " .$moduleName;

        return view('admin.drivers.index', compact(
            'rows', 
            'pendingDriversCount',
            'allDriversCount',
            'activeDriversCount',
            'blockedDriversCount',
            'moduleName',
            'pageTitle',
            'pageDes'
        ));
    }
    public function block($id)  
    {
        $this->model->findOrFail($id)->update([
            'status'    => 'blocked'
        ]);
        return redirect()->route('admin.drivers.index')->with('success', __('app.blocked_successfully'));
    }
    public function active($id)  
    {
        $this->model->findOrFail($id)->update([
            'status'    => 'active'
        ]);
        return redirect()->route('admin.drivers.index')->with('success', __('app.activated_successfully'));
    }
    public function edit($id)
    {
        $row = $this->model->findOrFail($id);
        $moduleName = $this->getModelName();
        $pageTitle = "Edit " . $moduleName;
        $pageDes = "Here you can edit " .$moduleName;
        $folderName = 'drivers';
        $routeName = 'drivers';
        $append = $this->append();
        return view('admin.drivers.edit', compact(
            'row',
            'pageTitle',
            'moduleName',
            'pageDes',
            'folderName',
            'routeName',
            'append'
        ));
    }

    public function show($id)  
    {
        $row = $this->model->with(['user', 'user.country', 'user.city'])->findOrFail($id);
        $moduleName = $this->getModelName();
        $pageTitle = $moduleName;
        $pageDes = "Here you can create " .$moduleName;
        
        // Get driver documents from API
        $driverDocuments = $this->getDriverDocuments($row->user_id);
        
        return view('admin.drivers.show', compact(
            'row', 
            'moduleName',
            'pageTitle',
            'pageDes',
            'driverDocuments'
        ));
    }
    
    private function getDriverDocuments($userId)
    {
        try {
            // Get user with all related documents
            $user = \App\Models\User::with([
                'profile', 
                'profile.car_licenses', 
                'profile.identity', 
                'profile.driver_licenses', 
                'profile.driver_cars'
            ])->find($userId);
            
            if (!$user || !$user->profile) {
                return null;
            }
            
            $documents = [];
            
            // Criminal record image
            if ($user->profile->criminal_record_image) {
                $documents[] = [
                    'type' => 'criminal_record',
                    'name' => 'سجل جنائي',
                    'image' => url('files/DriverLicense/' . $userId . '/' . $user->profile->criminal_record_image),
                    'status' => 'uploaded'
                ];
            }
            
            // Identity documents
            if ($user->profile->identity) {
                if ($user->profile->identity->front_side) {
                    $documents[] = [
                        'type' => 'identity_front',
                        'name' => 'الهوية - الوجه الأمامي',
                        'image' => url('files/DriverLicense/' . $userId . '/' . $user->profile->identity->front_side),
                        'status' => 'uploaded'
                    ];
                }
                if ($user->profile->identity->back_side) {
                    $documents[] = [
                        'type' => 'identity_back',
                        'name' => 'الهوية - الوجه الخلفي',
                        'image' => url('files/DriverLicense/' . $userId . '/' . $user->profile->identity->back_side),
                        'status' => 'uploaded'
                    ];
                }
            }
            
            // Driver licenses
            if ($user->profile->driver_licenses) {
                foreach ($user->profile->driver_licenses as $license) {
                    if ($license->front_side) {
                        $documents[] = [
                            'type' => 'license_front',
                            'name' => 'رخصة القيادة - الوجه الأمامي',
                            'image' => url('files/DriverLicense/' . $userId . '/' . $license->front_side),
                            'status' => 'uploaded'
                        ];
                    }
                    if ($license->back_side) {
                        $documents[] = [
                            'type' => 'license_back',
                            'name' => 'رخصة القيادة - الوجه الخلفي',
                            'image' => url('files/DriverLicense/' . $userId . '/' . $license->back_side),
                            'status' => 'uploaded'
                        ];
                    }
                }
            }
            
            // Car licenses
            if ($user->profile->car_licenses) {
                foreach ($user->profile->car_licenses as $carLicense) {
                    if ($carLicense->front_side) {
                        $documents[] = [
                            'type' => 'car_license_front',
                            'name' => 'رخصة السيارة - الوجه الأمامي',
                            'image' => url('files/DriverLicense/' . $userId . '/' . $carLicense->front_side),
                            'status' => 'uploaded'
                        ];
                    }
                    if ($carLicense->back_side) {
                        $documents[] = [
                            'type' => 'car_license_back',
                            'name' => 'رخصة السيارة - الوجه الخلفي',
                            'image' => url('files/DriverLicense/' . $userId . '/' . $carLicense->back_side),
                            'status' => 'uploaded'
                        ];
                    }
                }
            }
            
            return $documents;
            
        } catch (\Exception $e) {
            \Log::error('Error fetching driver documents: ' . $e->getMessage());
            return [];
        }
    }

    public function update(Request $request, $id)
    {
        $driverProfile = $this->model->findOrFail($id);
        
        // Validate the request
        $request->validate([
            'status' => 'required|in:pending,active,blocked',
            'birth_date' => 'required|date',
            'id_number' => 'required|string|max:50',
            'criminal_record_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user.full_name' => 'required|string|max:255',
            'user.email' => 'required|email|max:255',
            'user.phone_number' => 'nullable|string|max:20',
            'user.wallet_amount' => 'nullable|numeric|min:0',
        ]);

        // Update driver profile
        $driverProfile->update([
            'status' => $request->status,
            'birth_date' => $request->birth_date,
            'id_number' => $request->id_number,
        ]);

        // Handle criminal record image upload
        if ($request->hasFile('criminal_record_image')) {
            $image = $request->file('criminal_record_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/drivers'), $imageName);
            $driverProfile->update(['criminal_record_image' => 'uploads/drivers/' . $imageName]);
        }

        // Update user information
        $driverProfile->user->update([
            'full_name' => $request->input('user.full_name'),
            'email' => $request->input('user.email'),
            'phone_number' => $request->input('user.phone_number'),
            'wallet_amount' => $request->input('user.wallet_amount', 0),
        ]);

        return redirect()->route('admin.drivers.show', $driverProfile)
            ->with('success', __('global.updated_successfully'));
    }
}
