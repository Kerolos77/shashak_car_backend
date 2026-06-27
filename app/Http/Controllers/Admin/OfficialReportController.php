<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DriverProfile;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;

class OfficialReportController extends Controller
{
    /**
     * Export official security report PDF for a client (User)
     */
    public function exportUserPdf($id)
    {
        $user = User::with(['roles', 'identity', 'referrer', 'country', 'city'])->findOrFail($id);
        
        // Fetch identity documents
        $documents = $this->getUserDocuments($id);
        
        return $this->generatePdfReport($user, null, $documents, 'client');
    }
    
    /**
     * Export official security report PDF for a driver
     */
    public function exportDriverPdf($id)
    {
        // $id is driver profile ID, get driver profile first
        $profile = DriverProfile::with([
            'user', 
            'user.country', 
            'user.city', 
            'car_licenses', 
            'identity', 
            'driver_licenses', 
            'driver_cars', 
            'driver_cars.brand', 
            'driver_cars.model'
        ])->findOrFail($id);
        
        $user = $profile->user;
        
        // Get driver documents
        $documents = $this->getDriverDocuments($user->id);
        
        return $this->generatePdfReport($user, $profile, $documents, 'driver');
    }
    
    /**
     * Retrieve and convert client identity documents to base64
     */
    private function getUserDocuments($userId)
    {
        $documents = [];
        try {
            $user = User::with('identity')->find($userId);
            if ($user && $user->identity) {
                $identity = $user->identity;
                
                if ($identity->front_image) {
                    $documents[] = [
                        'name' => 'الهوية - الوجه الأمامي (Identity Front)',
                        'image' => $this->imageToBase64(public_path('files/UserIdentity/' . $userId . '/' . $identity->front_image)),
                    ];
                }
                if ($identity->back_image) {
                    $documents[] = [
                        'name' => 'الهوية - الوجه الخلفي (Identity Back)',
                        'image' => $this->imageToBase64(public_path('files/UserIdentity/' . $userId . '/' . $identity->back_image)),
                    ];
                }
                if ($identity->selfie_image) {
                    $documents[] = [
                        'name' => 'صورة سيلفي للتحقق (Selfie Verification)',
                        'image' => $this->imageToBase64(public_path('files/UserIdentity/' . $userId . '/' . $identity->selfie_image)),
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('PDF Export: Error fetching user documents: ' . $e->getMessage());
        }
        return array_filter($documents);
    }
    
    /**
     * Retrieve and convert driver documents (License, Criminal Record, etc.) to base64
     */
    private function getDriverDocuments($userId)
    {
        $documents = [];
        try {
            $user = User::with([
                'profile', 
                'profile.car_licenses', 
                'profile.identity', 
                'profile.driver_licenses'
            ])->find($userId);
            
            if ($user && $user->profile) {
                $profile = $user->profile;
                
                // Criminal record
                if ($profile->criminal_record_image) {
                    $path = $profile->criminal_record_image;
                    $fullPath = (str_starts_with($path, 'uploads/') || str_starts_with($path, 'http'))
                        ? public_path($path)
                        : public_path('files/DriverLicense/' . $userId . '/' . $path);
                    
                    $documents[] = [
                        'name' => 'السجل الجنائي (Criminal Record / فيش جنائي)',
                        'image' => $this->imageToBase64($fullPath),
                    ];
                }
                
                // Identity
                if ($profile->identity) {
                    $identity = $profile->identity;
                    if ($identity->front_identity_image) {
                        $documents[] = [
                            'name' => 'الهوية - الوجه الأمامي (Identity Front)',
                            'image' => $this->imageToBase64(public_path('files/DriverLicense/' . $userId . '/' . $identity->front_identity_image)),
                        ];
                    }
                    if ($identity->back_identity_image) {
                        $documents[] = [
                            'name' => 'الهوية - الوجه الخلفي (Identity Back)',
                            'image' => $this->imageToBase64(public_path('files/DriverLicense/' . $userId . '/' . $identity->back_identity_image)),
                        ];
                    }
                    if ($identity->driver_image_with_id) {
                        $documents[] = [
                            'name' => 'صورة السائق مع الهوية (Selfie with ID)',
                            'image' => $this->imageToBase64(public_path('files/DriverLicense/' . $userId . '/' . $identity->driver_image_with_id)),
                        ];
                    }
                }
                
                // Driver licenses
                if ($profile->driver_licenses) {
                    $license = $profile->driver_licenses;
                    if ($license->front_license_image) {
                        $documents[] = [
                            'name' => 'رخصة القيادة - الوجه الأمامي (Driver License Front)',
                            'image' => $this->imageToBase64(public_path('files/DriverLicense/' . $userId . '/' . $license->front_license_image)),
                        ];
                    }
                    if ($license->back_license_image) {
                        $documents[] = [
                            'name' => 'رخصة القيادة - الوجه الخلفي (Driver License Back)',
                            'image' => $this->imageToBase64(public_path('files/DriverLicense/' . $userId . '/' . $license->back_license_image)),
                        ];
                    }
                    if ($license->driver_with_license_image) {
                        $documents[] = [
                            'name' => 'صورة السائق مع رخصة القيادة (Selfie with License)',
                            'image' => $this->imageToBase64(public_path('files/DriverLicense/' . $userId . '/' . $license->driver_with_license_image)),
                        ];
                    }
                }
                
                // Car licenses
                if ($profile->car_licenses) {
                    $carLicense = $profile->car_licenses;
                    if ($carLicense->front_license_image) {
                        $documents[] = [
                            'name' => 'رخصة السيارة - الوجه الأمامي (Car License Front)',
                            'image' => $this->imageToBase64(public_path('files/DriverLicense/' . $userId . '/' . $carLicense->front_license_image)),
                        ];
                    }
                    if ($carLicense->back_license_image) {
                        $documents[] = [
                            'name' => 'رخصة السيارة - الوجه الخلفي (Car License Back)',
                            'image' => $this->imageToBase64(public_path('files/DriverLicense/' . $userId . '/' . $carLicense->back_license_image)),
                        ];
                    }
                    if ($carLicense->driver_with_license_image) {
                        $documents[] = [
                            'name' => 'صورة السائق مع رخصة السيارة (Selfie with Car License)',
                            'image' => $this->imageToBase64(public_path('files/DriverLicense/' . $userId . '/' . $carLicense->driver_with_license_image)),
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('PDF Export: Error fetching driver documents: ' . $e->getMessage());
        }
        return array_filter($documents);
    }
    
    /**
     * Helper to encode image to base64
     */
    private function imageToBase64($path)
    {
        try {
            if (file_exists($path) && is_file($path)) {
                $data = file_get_contents($path);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        } catch (\Exception $e) {
            Log::error("PDF Export: Failed to convert image to base64: " . $e->getMessage());
        }
        return null;
    }
    
    /**
     * Generate and stream PDF Report using Dompdf
     */
    private function generatePdfReport($user, $profile, $documents, $type)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        
        $latitude = $user->latitude;
        $longitude = $user->longitude;
        
        $mapLink = null;
        if ($latitude && $longitude) {
            $mapLink = "https://www.google.com/maps/search/?api=1&query={$latitude},{$longitude}";
        }
        
        $avatarBase64 = null;
        if ($user->profile_pic) {
            $path = $user->profile_pic;
            $avatarPath = (str_starts_with($path, 'uploads/') || str_starts_with($path, 'http'))
                ? public_path($path)
                : public_path('files/users/' . $user->id . '/' . $path);
            $avatarBase64 = $this->imageToBase64($avatarPath);
        }
        
        $html = view('admin.reports.official_report', compact(
            'user',
            'profile',
            'documents',
            'type',
            'latitude',
            'longitude',
            'mapLink',
            'avatarBase64'
        ))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = "shashak_report_" . $type . "_" . $user->id . "_" . date('Ymd_His') . ".pdf";
        
        // Output the generated PDF to Browser (inline view)
        return $dompdf->stream($filename, ["Attachment" => false]);
    }
}
