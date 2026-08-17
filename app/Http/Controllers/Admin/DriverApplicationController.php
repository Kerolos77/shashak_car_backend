<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverProfile;
use App\Models\DriverRegistrationLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $pageTitle = 'مركز مراجعة طلبات الانضمام والمستندات';

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

        // Auto-heal database table columns if missing
        $this->ensureSchemaColumns();

        // Get User profile picture (checks profile_pic, photo, avatar, image)
        $userPhotoFile = $user->profile_pic ?? $user->photo ?? $user->avatar ?? $user->image ?? null;

        $identity = $driver->identity;
        $license = $driver->driver_licenses;
        $carLicense = $driver->car_licenses;
        $car = $driver->driver_cars;

        // Document paths resolving with fallback
        $documents = [
            'personal_photo' => $this->resolveFileUrl($userPhotoFile, $user->id),
            'id_front' => $this->resolveFileUrl($identity->front_identity_image ?? $identity->id_photo_front ?? $identity->front_image ?? null, $user->id),
            'id_back' => $this->resolveFileUrl($identity->back_identity_image ?? $identity->id_photo_back ?? $identity->back_image ?? null, $user->id),
            'license_front' => $this->resolveFileUrl($license->front_license_image ?? $license->license_photo_front ?? null, $user->id),
            'license_back' => $this->resolveFileUrl($license->back_license_image ?? $license->license_photo_back ?? null, $user->id),
            'car_license_front' => $this->resolveFileUrl($carLicense->front_license_image ?? $carLicense->car_license_photo_front ?? null, $user->id),
            'car_license_back' => $this->resolveFileUrl($carLicense->back_license_image ?? $carLicense->car_license_photo_back ?? null, $user->id),
            'car_front' => $this->resolveFileUrl($car->car_photo_front ?? $car->car_image ?? $car->front_image ?? null, $user->id),
            'car_back' => $this->resolveFileUrl($car->car_photo_back ?? $car->back_image ?? null, $user->id),
            'criminal_record' => $this->resolveFileUrl($driver->criminal_record_image ?? $driver->criminal_record_photo ?? null, $user->id),
        ];

        // Fetch latest rejection reason with log fallback
        $latestRejectionLog = null;
        if (Schema::hasTable('driver_registration_logs')) {
            $latestRejectionLog = DriverRegistrationLog::where('driver_profile_id', $driver->id)
                ->where('action', 'rejected')
                ->orderBy('id', 'desc')
                ->first();
        }

        $rejectionReason = $driver->latest_rejection_reason ?? ($latestRejectionLog ? $latestRejectionLog->reason : null);

        $pageTitle = 'مراجعة طلب انضمام السائق: ' . ($user->full_name ?? '#' . $driver->id);

        return view('admin.driver_applications.show', compact('driver', 'user', 'documents', 'rejectionReason', 'pageTitle'));
    }

    /**
     * Approve driver application.
     */
    public function approve($id)
    {
        $this->ensureSchemaColumns();

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

        $this->ensureSchemaColumns();

        $driverProfile = DriverProfile::findOrFail($id);

        // Update driver profile status and reason
        $driverProfile->status = 'rejected';
        $driverProfile->latest_rejection_reason = $request->reason;
        $driverProfile->save();

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

    /**
     * Auto-heal database schema columns if missing in MySQL.
     */
    private function ensureSchemaColumns()
    {
        if (Schema::hasTable('driver_profiles')) {
            if (!Schema::hasColumn('driver_profiles', 'latest_rejection_reason')) {
                try {
                    DB::statement("ALTER TABLE `driver_profiles` ADD COLUMN `latest_rejection_reason` TEXT NULL AFTER `status`");
                } catch (\Exception $e) {}
            }

            try {
                DB::statement("ALTER TABLE `driver_profiles` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'pending'");
            } catch (\Exception $e) {}
        }
    }

    /**
     * Resolve document or image URL safely.
     */
    private function resolveFileUrl($filename, $userId = null)
    {
        if (empty($filename)) {
            return asset('assets/media/avatars/blank.png');
        }

        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }

        if (str_starts_with($filename, 'uploads/') || str_starts_with($filename, 'files/')) {
            return url($filename);
        }

        if ($userId) {
            $driverPath = public_path('files/DriverLicense/' . $userId . '/' . $filename);
            if (file_exists($driverPath)) {
                return url('files/DriverLicense/' . $userId . '/' . $filename);
            }
        }

        if (file_exists(public_path('uploads/' . $filename))) {
            return url('uploads/' . $filename);
        }

        if (file_exists(public_path($filename))) {
            return url($filename);
        }

        return $userId ? url('files/DriverLicense/' . $userId . '/' . $filename) : url('uploads/' . $filename);
    }
}
