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
        $vipDriversCount = $this->model->whereHas('user', function($q) { $q->where('is_vip', 1); })->count();

        $rows = DriverProfile::with(['user', 'driver_cars', 'service']); 
        
        if ($request->status != null && $request->status != '') {
            $rows = $rows->where('status', $request->status);
        }

        if ($request->has('is_vip') && $request->is_vip !== null && $request->is_vip !== '') {
            $isVipVal = $request->is_vip == '1' ? 1 : 0;
            $rows = $rows->whereHas('user', function($q) use ($isVipVal) {
                $q->where('is_vip', $isVipVal);
            });
        }

        if ($request->search != null && $request->search != '') {
            $searchTerm = '%' . $request->search . '%';
            $rows = $rows->where(function($q) use ($searchTerm) {
                $q->whereHas('user', function($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', $searchTerm)
                              ->orWhere('phone_number', 'like', $searchTerm)
                              ->orWhere('email', 'like', $searchTerm);
                })->orWhereHas('driver_cars', function($carQuery) use ($searchTerm) {
                    $carQuery->where('car_number', 'like', $searchTerm);
                })->orWhere('id_number', 'like', $searchTerm);
            });
        }
        
        if ($request->service_id != null && $request->service_id != '') {
            $rows = $rows->where('service_id', $request->service_id);
        }

        $rows = $rows->orderBy('id', 'desc')->paginate(10);
        $moduleName = $this->getModelName();
        $pageTitle = $moduleName;
        $pageDes = "Here you can create " .$moduleName;
        $services = \App\Models\Service::pluck('title', 'id');

        return view('admin.drivers.index', compact(
            'rows', 
            'pendingDriversCount',
            'allDriversCount',
            'activeDriversCount',
            'blockedDriversCount',
            'vipDriversCount',
            'moduleName',
            'pageTitle',
            'pageDes',
            'services'
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
        $userId = $row->user_id;

        $moduleName = $this->getModelName();
        $pageTitle = $moduleName;
        $pageDes = "Here you can create " .$moduleName;
        
        // Get driver documents from API
        $driverDocuments = $this->getDriverDocuments($userId);
        
        // Order Stats
        $orderStats = [
            'total' => \App\Models\Order::where('driver_id', $userId)->count(),
            'completed' => \App\Models\Order::where('driver_id', $userId)->where('status', \App\Models\Order::STATUS_COMPLETED)->count(),
            'canceled' => \App\Models\Order::where('driver_id', $userId)->where('status', \App\Models\Order::STATUS_CANCELED)->count(),
            'total_earnings' => \App\Models\Order::where('driver_id', $userId)->where('status', \App\Models\Order::STATUS_COMPLETED)->sum('final_rate'),
        ];

        // Reviews received
        $reviews = \App\Models\Review::where('to_user_id', $userId)->with('fromUser')->orderBy('id', 'desc')->get();

        // Active Packages & Purchases
        $activePackages = \App\Models\Purchase::where('driver_id', $userId)->with('package')->orderBy('id', 'desc')->get();
        $availablePackages = \App\Models\Package::where('is_active', 1)->get();

        // Audit logs
        $auditLogs = \App\Models\AdminUserAuditLog::where('user_id', $userId)->with('admin')->orderBy('id', 'desc')->get();

        return view('admin.drivers.show', compact(
            'row', 
            'moduleName',
            'pageTitle',
            'pageDes',
            'driverDocuments',
            'orderStats',
            'reviews',
            'activePackages',
            'availablePackages',
            'auditLogs'
        ));
    }

    public function resetCashBan($id)
    {
        $driverProfile = $this->model->findOrFail($id);
        $user = \App\Models\User::findOrFail($driverProfile->user_id);

        $user->cash_restriction_seconds_remaining = 0;
        $user->save();

        \App\Models\AdminUserAuditLog::create([
            'admin_id' => \Illuminate\Support\Facades\Auth::guard('admin')->id() ?? \Illuminate\Support\Facades\Auth::id(),
            'user_id'  => $user->id,
            'action'   => 'reset_cash_ban',
            'notes'    => 'تم تصفير وإلغاء حظر الكاش فوراً عن السائق بواسطة الإدارة.',
        ]);

        return redirect()->back()->with('success', __('تم فك وتصفير حظر الكاش عن السائق بنجاح.'));
    }

    public function toggleVip($id)
    {
        $driverProfile = $this->model->findOrFail($id);
        $user = \App\Models\User::findOrFail($driverProfile->user_id);

        $user->is_vip = !$user->is_vip;
        $user->save();

        $statusText = $user->is_vip ? 'تفعيل شارة السائق المميز (VIP)' : 'إلغاء شارة VIP';

        \App\Models\AdminUserAuditLog::create([
            'admin_id' => \Illuminate\Support\Facades\Auth::guard('admin')->id() ?? \Illuminate\Support\Facades\Auth::id(),
            'user_id'  => $user->id,
            'action'   => 'toggle_vip',
            'notes'    => "تم {$statusText} للسائق.",
        ]);

        return redirect()->back()->with('success', __("تم {$statusText} بنجاح."));
    }

    public function addWalletBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'notes' => 'nullable|string|max:255',
        ]);

        $driverProfile = $this->model->findOrFail($id);
        $user = \App\Models\User::findOrFail($driverProfile->user_id);

        $amount = (float)$request->amount;
        $user->wallet_amount = ($user->wallet_amount ?? 0) + $amount;
        $user->save();

        \App\Models\WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => abs($amount),
            'type' => $amount >= 0 ? 'deposit' : 'withdraw',
            'status' => 'completed',
            'notes' => $request->notes ?? 'إضافة/خصم رصيد يدوي من الإدارة',
        ]);

        \App\Models\AdminUserAuditLog::create([
            'admin_id' => \Illuminate\Support\Facades\Auth::guard('admin')->id() ?? \Illuminate\Support\Facades\Auth::id(),
            'user_id'  => $user->id,
            'action'   => 'add_wallet',
            'notes'    => "تم تغيير رصيد المحفظة بمبلغ ({$amount} ج.م) - السبب: " . ($request->notes ?? 'إضافة يدوية'),
        ]);

        return redirect()->back()->with('success', __('تم تعديل رصيد محفظة السائق وتسجيل المعاملة بنجاح.'));
    }

    public function giftPackage(Request $request, $id)
    {
        $request->validate([
            'package_id' => 'required|exists:driver_packages,id',
        ]);

        $driverProfile = $this->model->findOrFail($id);
        $user = \App\Models\User::findOrFail($driverProfile->user_id);
        $package = \App\Models\Package::findOrFail($request->package_id);

        $expiresAt = now()->addDays($package->duration_days ?? 30);

        \App\Models\Purchase::create([
            'driver_id' => $user->id,
            'package_id' => $package->id,
            'expires_at' => $expiresAt,
            'status' => 'active',
        ]);

        \App\Models\AdminUserAuditLog::create([
            'admin_id' => \Illuminate\Support\Facades\Auth::guard('admin')->id() ?? \Illuminate\Support\Facades\Auth::id(),
            'user_id'  => $user->id,
            'action'   => 'gift_package',
            'notes'    => "تم إهداء وتفعيل باقة ({$package->name}) مجاناً للسائق لغايـة " . $expiresAt->format('Y-m-d'),
        ]);

        return redirect()->back()->with('success', __('تم إهداء وتفعيل الباقة للسائق بنجاح.'));
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
                return [];
            }
            
            $documents = [];
            
            // Criminal record image
            if ($user->profile->criminal_record_image) {
                $img = $user->profile->criminal_record_image;
                $imageUrl = (str_starts_with($img, 'uploads/') || str_starts_with($img, 'http'))
                    ? url($img)
                    : url('files/DriverLicense/' . $userId . '/' . $img);
                $documents[] = [
                    'type' => 'criminal_record',
                    'name' => 'سجل جنائي',
                    'image' => $imageUrl,
                    'status' => 'uploaded'
                ];
            }
            
            // Identity documents
            if ($user->profile->identity) {
                $identity = $user->profile->identity;
                if ($identity->front_identity_image) {
                    $documents[] = [
                        'type' => 'identity_front',
                        'name' => 'الهوية - الوجه الأمامي',
                        'image' => url('files/DriverLicense/' . $userId . '/' . $identity->front_identity_image),
                        'status' => 'uploaded'
                    ];
                }
                if ($identity->back_identity_image) {
                    $documents[] = [
                        'type' => 'identity_back',
                        'name' => 'الهوية - الوجه الخلفي',
                        'image' => url('files/DriverLicense/' . $userId . '/' . $identity->back_identity_image),
                        'status' => 'uploaded'
                    ];
                }
                if ($identity->driver_image_with_id) {
                    $documents[] = [
                        'type' => 'identity_with_driver',
                        'name' => 'صورة السائق مع الهوية',
                        'image' => url('files/DriverLicense/' . $userId . '/' . $identity->driver_image_with_id),
                        'status' => 'uploaded'
                    ];
                }
            }
            
            // Driver licenses
            if ($user->profile->driver_licenses) {
                $license = $user->profile->driver_licenses;
                if ($license->front_license_image) {
                    $documents[] = [
                        'type' => 'license_front',
                        'name' => 'رخصة القيادة - الوجه الأمامي',
                        'image' => url('files/DriverLicense/' . $userId . '/' . $license->front_license_image),
                        'status' => 'uploaded'
                    ];
                }
                if ($license->back_license_image) {
                    $documents[] = [
                        'type' => 'license_back',
                        'name' => 'رخصة القيادة - الوجه الخلفي',
                        'image' => url('files/DriverLicense/' . $userId . '/' . $license->back_license_image),
                        'status' => 'uploaded'
                    ];
                }
                if ($license->driver_with_license_image) {
                    $documents[] = [
                        'type' => 'license_with_driver',
                        'name' => 'صورة السائق مع رخصة القيادة',
                        'image' => url('files/DriverLicense/' . $userId . '/' . $license->driver_with_license_image),
                        'status' => 'uploaded'
                    ];
                }
            }
            
            // Car licenses
            if ($user->profile->car_licenses) {
                $carLicense = $user->profile->car_licenses;
                if ($carLicense->front_license_image) {
                    $documents[] = [
                        'type' => 'car_license_front',
                        'name' => 'رخصة السيارة - الوجه الأمامي',
                        'image' => url('files/DriverLicense/' . $userId . '/' . $carLicense->front_license_image),
                        'status' => 'uploaded'
                    ];
                }
                if ($carLicense->back_license_image) {
                    $documents[] = [
                        'type' => 'car_license_back',
                        'name' => 'رخصة السيارة - الوجه الخلفي',
                        'image' => url('files/DriverLicense/' . $userId . '/' . $carLicense->back_license_image),
                        'status' => 'uploaded'
                    ];
                }
                if ($carLicense->driver_with_license_image) {
                    $documents[] = [
                        'type' => 'car_license_with_driver',
                        'name' => 'صورة السائق مع رخصة السيارة',
                        'image' => url('files/DriverLicense/' . $userId . '/' . $carLicense->driver_with_license_image),
                        'status' => 'uploaded'
                    ];
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
