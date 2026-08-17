<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverProfile;
use App\Models\DriverRegistrationLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DriverApplicationController extends Controller
{
    /**
     * Display a listing of driver registration applications.
     */
    public function index(Request $request)
    {
        $query = DriverProfile::with(['user', 'driver_cars', 'service']);

        // Default filter for onboarding page is pending if no status specified
        $status = $request->get('status', 'pending');
        if ($status !== 'all' && !empty($status)) {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', $search)
                              ->orWhere('phone_number', 'like', $search)
                              ->orWhere('email', 'like', $search);
                })->orWhereHas('driver_cars', function ($carQuery) use ($search) {
                    $carQuery->where('car_number', 'like', $search);
                })->orWhere('id_number', 'like', $search);
            });
        }

        $rows = $query->orderBy('id', 'desc')->paginate(15);

        // Counts
        $pendingCount = DriverProfile::where('status', 'pending')->count();
        $rejectedCount = DriverProfile::where('status', 'rejected')->count();
        $activeCount = DriverProfile::where('status', 'active')->count();
        $totalCount = DriverProfile::count();

        $pageTitle = 'طلبات انضمام السائقين والمستندات';

        return view('admin.driver_applications.index', compact(
            'rows',
            'pendingCount',
            'rejectedCount',
            'activeCount',
            'totalCount',
            'pageTitle'
        ));
    }

    /**
     * Display the document comparison and review page for a specific application.
     */
    public function show($id)
    {
        $withRelations = ['user', 'user.country', 'user.city', 'driver_cars', 'car_licenses', 'identity', 'driver_licenses', 'service'];
        if (Schema::hasTable('driver_registration_logs')) {
            $withRelations[] = 'registration_logs';
            $withRelations[] = 'registration_logs.admin';
        }

        $driver = DriverProfile::with($withRelations)->findOrFail($id);
        $user = $driver->user;

        // Get driver documents array/paths
        $documents = [
            'personal_photo' => asset($user->photo ?? 'assets/media/avatars/blank.png'),
            'id_front' => asset($driver->identity->id_photo_front ?? 'assets/media/avatars/blank.png'),
            'id_back' => asset($driver->identity->id_photo_back ?? 'assets/media/avatars/blank.png'),
            'license_front' => asset($driver->driver_licenses->license_photo_front ?? 'assets/media/avatars/blank.png'),
            'license_back' => asset($driver->driver_licenses->license_photo_back ?? 'assets/media/avatars/blank.png'),
            'car_license_front' => asset($driver->car_licenses->car_license_photo_front ?? 'assets/media/avatars/blank.png'),
            'car_license_back' => asset($driver->car_licenses->car_license_photo_back ?? 'assets/media/avatars/blank.png'),
            'car_front' => asset($driver->driver_cars->car_photo_front ?? 'assets/media/avatars/blank.png'),
            'car_back' => asset($driver->driver_cars->car_photo_back ?? 'assets/media/avatars/blank.png'),
            'criminal_record' => asset($driver->criminal_record_photo ?? 'assets/media/avatars/blank.png'),
        ];

        $pageTitle = 'مراجعة طلب انضمام السائق: ' . ($user->full_name ?? '#' . $driver->id);

        return view('admin.driver_applications.show', compact('driver', 'user', 'documents', 'pageTitle'));
    }

    /**
     * Approve driver application.
     */
    public function approve($id)
    {
        $driverProfile = DriverProfile::findOrFail($id);
        $driverProfile->update([
            'status' => 'active',
            'latest_rejection_reason' => null,
        ]);

        if (Schema::hasTable('driver_registration_logs')) {
            try {
                DriverRegistrationLog::create([
                    'driver_profile_id' => $driverProfile->id,
                    'admin_id' => Auth::guard('admin')->id() ?? Auth::id(),
                    'action' => 'approved',
                    'reason' => 'تم قبول جميع المستندات وتفعيل حساب السائق للانضمام.',
                ]);
            } catch (\Exception $e) {}
        }

        return redirect()->back()->with('success', 'تم قبول مستندات السائق وتفعيل الحساب بنجاح.');
    }

    /**
     * Reject driver application with a custom/predefined reason.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $driverProfile = DriverProfile::findOrFail($id);
        $driverProfile->update([
            'status' => 'rejected',
            'latest_rejection_reason' => $request->reason,
        ]);

        if (Schema::hasTable('driver_registration_logs')) {
            try {
                DriverRegistrationLog::create([
                    'driver_profile_id' => $driverProfile->id,
                    'admin_id' => Auth::guard('admin')->id() ?? Auth::id(),
                    'action' => 'rejected',
                    'reason' => $request->reason,
                ]);
            } catch (\Exception $e) {}
        }

        return redirect()->back()->with('success', 'تم تسجيل رفض طلب الانضمام وإرسال السبب بنجاح.');
    }
}
