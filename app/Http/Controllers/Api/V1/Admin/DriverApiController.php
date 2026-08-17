<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\DriverCar;
use Illuminate\Http\Request;
use App\Models\DriverLicense;
use App\Models\DriverProfile;
use Illuminate\Http\Response;
use App\Models\DriverIdentity;
use App\Helpers\ResponseHelper;
use App\Models\DriverCarLicense;
use App\Models\DriverRegistrationLog;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ImageProcessing;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class DriverApiController extends Controller
{
    use ResponseHelper, ImageProcessing;

    public function check_registration_status(Request $request)
    {
        try {
            $user_id = Auth::id() ?? $this->getUserIDByToken(request()->bearerToken());
            if (!$user_id) {
                return $this->apiResponseHandler(401, false, 'Unauthenticated');
            }

            $profile = DriverProfile::where('user_id', $user_id)->first();
            if (!$profile) {
                return $this->apiResponseHandler(200, true, 'Status retrieved successfully', [
                    'registration_status' => 'not_submitted',
                    'rejection_reason' => null,
                    'message' => 'لم يتم تقديم طلب تسجيل بعد',
                ]);
            }

            $status = $profile->status ?? 'pending';
            $rejectionReason = $profile->latest_rejection_reason;

            $message = 'طلبك قيد المراجعة حالياً من قبل الإدارة';
            if ($status === 'active' || $status === 'approved') {
                $message = 'تم تفعيل حسابك كـ سائق بنجاح';
            } elseif ($status === 'rejected') {
                $message = $rejectionReason ?? 'تم رفض طلب التسجيل، يرجى مراجعة المستندات وإعادة الإرسال';
            }

            return $this->apiResponseHandler(200, true, 'Status retrieved successfully', [
                'registration_status' => $status,
                'rejection_reason' => $rejectionReason,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return $this->apiResponseHandler(500, false, 'Error checking status', $e->getMessage());
        }
    }

    public function driver_registration(Request $request)
    {
        // Increase execution time for this request
        set_time_limit(300);
        
        DB::beginTransaction();

        try {
            $user_id = Auth::id() ?? $this->getUserIDByToken(request()->bearerToken());
            if (!$user_id) {
                return $this->apiResponseHandler(401, false, 'Unauthenticated');
            }
            
            // Process images in parallel array to avoid sequential processing
            $images = [];
            $imageFields = [
                'criminal_record_image',
                'front_identity_image',
                'back_identity_image',
                'driver_image_with_id',
                'front_license_image',
                'back_license_image',
                'driver_with_license_image',
                'car_front_license_image',
                'car_back_license_image',
                'car_driver_with_license_image',
                'car_image'
            ];
            
            // Collect all images first (faster)
            foreach ($imageFields as $field) {
                if ($request->has($field)) {
                    $images[$field] = $request->$field;
                }
            }
            
            // Process images
            $processedImages = [];
            foreach ($images as $field => $image) {
                $processedImages[$field] = $this->saveImageAndThumbnail(
                    $image, 
                    false, 
                    $user_id, 
                    'DriverLicense'
                );
            }

            // Update or Create DriverProfile
            $profileData = [
                'birth_date' => $request->birth_date,
                'id_number' => $request->id_number,
                'status' => 'pending',
                'latest_rejection_reason' => null,
            ];

            if (isset($processedImages['criminal_record_image']['image'])) {
                $profileData['criminal_record_image'] = $processedImages['criminal_record_image']['image'];
            }
            if ($request->filled('criminal_expiry_date')) {
                $profileData['expiry_date'] = $request->criminal_expiry_date;
            }
            if ($request->filled('service_id')) {
                $profileData['service_id'] = $request->service_id;
            }

            $DriverProfile = DriverProfile::updateOrCreate(
                ['user_id' => $user_id],
                $profileData
            );

            // DriverIdentity
            $identityData = [
                'id_number' => $request->id_number,
                'expiry_date' => $request->expiry_date,
            ];
            if (isset($processedImages['front_identity_image']['image'])) {
                $identityData['front_identity_image'] = $processedImages['front_identity_image']['image'];
            }
            if (isset($processedImages['back_identity_image']['image'])) {
                $identityData['back_identity_image'] = $processedImages['back_identity_image']['image'];
            }
            if (isset($processedImages['driver_image_with_id']['image'])) {
                $identityData['driver_image_with_id'] = $processedImages['driver_image_with_id']['image'];
            }

            $DriverIdentity = DriverIdentity::updateOrCreate(
                ['driver_profile_id' => $DriverProfile->id],
                $identityData
            );

            // DriverCar
            $carData = [
                'car_brand_id' => $request->car_brand_id ?? 1,
                'car_model_id' => $request->car_model_id ?? 1,
                'color' => $request->color,
                'release_year' => $request->release_year
            ];

            $DriverCar = DriverCar::updateOrCreate(
                ['driver_profile_id' => $DriverProfile->id],
                $carData
            );

            // DriverLicense
            $licenseData = [
                'expiry_date' => $request->license_expiry_date,
            ];
            if (isset($processedImages['front_license_image']['image'])) {
                $licenseData['front_license_image'] = $processedImages['front_license_image']['image'];
            }
            if (isset($processedImages['back_license_image']['image'])) {
                $licenseData['back_license_image'] = $processedImages['back_license_image']['image'];
            }
            if (isset($processedImages['driver_with_license_image']['image'])) {
                $licenseData['driver_with_license_image'] = $processedImages['driver_with_license_image']['image'];
            }

            $DriverLicense = DriverLicense::updateOrCreate(
                ['driver_profile_id' => $DriverProfile->id],
                $licenseData
            );

            // DriverCarLicense
            $carLicenseData = [
                'expiry_date' => $request->car_expiry_date,
                'driver_car_id' => $DriverCar->id
            ];
            if (isset($processedImages['car_front_license_image']['image'])) {
                $carLicenseData['front_license_image'] = $processedImages['car_front_license_image']['image'];
            }
            if (isset($processedImages['car_back_license_image']['image'])) {
                $carLicenseData['back_license_image'] = $processedImages['car_back_license_image']['image'];
            }
            if (isset($processedImages['car_driver_with_license_image']['image'])) {
                $carLicenseData['driver_with_license_image'] = $processedImages['car_driver_with_license_image']['image'];
            }

            $DriverCarLicense = DriverCarLicense::updateOrCreate(
                ['driver_profile_id' => $DriverProfile->id],
                $carLicenseData
            );

            // Log submission in history table
            DriverRegistrationLog::create([
                'driver_profile_id' => $DriverProfile->id,
                'admin_id' => null,
                'action' => 'resubmitted',
                'reason' => 'تم تقديم/تعديل طلب التسجيل من التطبيق',
            ]);

            DB::commit();
            
            return $this->apiResponseHandler(200, true, 'success');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Driver registration failed: ' . $e->getMessage(), [
                'user_id' => $user_id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->apiResponseHandler(400, false, 'error', $e->getMessage());
        }
    }

    public function change_service(Request $request)
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        
        DriverProfile::where('user_id', $driverID)->update([
            'service_id' => $request->service_id
        ]);
        return $this->apiResponseHandler(200, true, 'success');
    }

    public function getUserIDByToken($hashedToken)
    {
        $token = PersonalAccessToken::findToken($hashedToken);
        if ($token != null) {
            return $token->tokenable_id;
        } else {
            return false;
        }
    }

    public function setDestination(Request $request)
    {
        $request->validate([
            'is_heading_destination' => 'required|boolean',
            'destination_lat' => 'required_if:is_heading_destination,true|numeric',
            'destination_long' => 'required_if:is_heading_destination,true|numeric',
            'destination_address' => 'nullable|string',
        ]);

        $driverID = $this->getUserIDByToken(request()->bearerToken());
        
        $profile = DriverProfile::where('user_id', $driverID)->first();
        if (!$profile) {
            return $this->apiResponseHandler(404, false, 'Driver profile not found');
        }

        $profile->is_heading_destination = $request->is_heading_destination;
        if ($request->is_heading_destination) {
            $profile->destination_lat = $request->destination_lat;
            $profile->destination_long = $request->destination_long;
            $profile->destination_address = $request->destination_address;
        }
        $profile->save();

        return $this->apiResponseHandler(200, true, 'Destination updated successfully', [
            'is_heading_destination' => $profile->is_heading_destination,
            'destination_lat' => $profile->destination_lat,
            'destination_long' => $profile->destination_long,
            'destination_address' => $profile->destination_address,
        ]);
    }
}
