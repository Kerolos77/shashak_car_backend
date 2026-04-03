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
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ImageProcessing;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class DriverApiController extends Controller
{
    use ResponseHelper, ImageProcessing;
   public function driver_registration(Request $request)
{
    // Increase execution time for this request
    set_time_limit(300);
    
    DB::beginTransaction();

    try {
        $user_id = Auth::user()->id;
        
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
            'car_driver_with_license_image'
        ];
        
        // Collect all images first (faster)
        foreach ($imageFields as $field) {
            if ($request->has($field)) {
                $images[$field] = $request->$field;
            }
        }
        
        // Process images (consider using Jobs for async processing)
        $processedImages = [];
        foreach ($images as $field => $image) {
            $processedImages[$field] = $this->saveImageAndThumbnail(
                $image, 
                false, 
                $user_id, 
                'DriverLicense'
            );
        }

        // Create DriverProfile
        $DriverProfile = DriverProfile::create([
            'user_id' => $user_id,
            'birth_date' => $request->birth_date,
            'id_number' => $request->id_number,
            'criminal_record_image' => $processedImages['criminal_record_image']['image'] ?? null,
            'expiry_date' => $request->criminal_expiry_date,
            'service_id' => $request->service_id
        ]);

        // Use bulk insert where possible
        $DriverIdentity = DriverIdentity::create([
            'id_number' => $request->id_number,
            'front_identity_image' => $processedImages['front_identity_image']['image'] ?? null,
            'back_identity_image' => $processedImages['back_identity_image']['image'] ?? null,
            'expiry_date' => $request->expiry_date,
            'driver_image_with_id' => $processedImages['driver_image_with_id']['image'] ?? null,
            'driver_profile_id' => $DriverProfile->id,
        ]);

        $DriverCar = DriverCar::create([
            'driver_profile_id' => $DriverProfile->id,
            'car_brand_id' => $request->car_brand_id,
            'car_model_id' => $request->car_model_id,
            'color' => $request->color,
            'release_year' => $request->release_year
        ]);

        $DriverLicense = DriverLicense::create([
            'front_license_image' => $processedImages['front_license_image']['image'] ?? null,
            'back_license_image' => $processedImages['back_license_image']['image'] ?? null,
            'driver_with_license_image' => $processedImages['driver_with_license_image']['image'] ?? null,
            'expiry_date' => $request->license_expiry_date,
            'driver_profile_id' => $DriverProfile->id
        ]);

        $DriverCarLicense = DriverCarLicense::create([
            'front_license_image' => $processedImages['car_front_license_image']['image'] ?? null,
            'back_license_image' => $processedImages['car_back_license_image']['image'] ?? null,
            'driver_with_license_image' => $processedImages['car_driver_with_license_image']['image'] ?? null,
            'expiry_date' => $request->car_expiry_date,
            'driver_profile_id' => $DriverProfile->id,
            'driver_car_id' => $DriverCar->id
        ]);

        DB::commit();
        
        return $this->apiResponseHandler(200, true, 'success');
        
    } catch (\Exception $e) {
        DB::rollback();
        
        // Log the error for debugging
        \Log::error('Driver registration failed: ' . $e->getMessage(), [
            'user_id' => $user_id ?? null,
            'trace' => $e->getTraceAsString()
        ]);
        
        return $this->apiResponseHandler(400, false, 'error', $e->getMessage());
    }
}

// Alternative: Queue-based approach for heavy image processing
public function driver_registration_async(Request $request)
{
    $validatedData = $request->validate([
        // Add your validation rules here
        'birth_date' => 'required|date',
        'id_number' => 'required|string',
        // ... other fields
    ]);
    
    // Create a pending registration
    $registration = PendingDriverRegistration::create([
        'user_id' => Auth::id(),
        'data' => json_encode($validatedData),
        'status' => 'processing'
    ]);
    
    // Dispatch job for async processing
    ProcessDriverRegistration::dispatch($registration);
    
    return $this->apiResponseHandler(
        202, // Accepted
        true, 
        'Registration is being processed. You will be notified once complete.',
        ['registration_id' => $registration->id]
    );
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
}
