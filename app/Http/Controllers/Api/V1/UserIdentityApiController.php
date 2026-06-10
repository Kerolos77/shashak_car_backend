<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserIdentity;
use App\Services\GeminiVerificationService;
use App\Traits\ImageProcessing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserIdentityApiController extends Controller
{
    use ImageProcessing;

    protected $geminiService;

    public function __construct(GeminiVerificationService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Verify the user's identity documents using Gemini AI.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyIdentity(Request $request)
    {
        // Increase execution time limit for deep AI analysis
        set_time_limit(120);

        $validator = Validator::make($request->all(), [
            'front_image' => 'required|image|max:10240', // Max 10MB
            'back_image' => 'required|image|max:10240',
            'selfie_image' => 'required|image|max:10240',
            'id_number' => 'nullable|string|size:14', // Optional user input NID
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => [
                    'verification_status' => 'unverified',
                    'rejection_reason' => $validator->errors()->first(),
                ]
            ], 422);
        }

        try {
            $user = Auth::user();
            $userId = $user->id;

            // Delete existing user identity records and files to allow a fresh start
            $existingIdentity = UserIdentity::where('user_id', $userId)->first();
            if ($existingIdentity) {
                // Delete actual files from disk
                if ($existingIdentity->front_image) {
                    $this->deletefile($existingIdentity->front_image, $userId, 'UserIdentity');
                }
                if ($existingIdentity->back_image) {
                    $this->deletefile($existingIdentity->back_image, $userId, 'UserIdentity');
                }
                if ($existingIdentity->selfie_image) {
                    $this->deletefile($existingIdentity->selfie_image, $userId, 'UserIdentity');
                }
                $existingIdentity->delete();
            }

            // Save uploaded files to public/files/UserIdentity/{user_id}/
            $frontSaved = $this->saveImageAndThumbnail($request->file('front_image'), false, $userId, 'UserIdentity');
            $backSaved = $this->saveImageAndThumbnail($request->file('back_image'), false, $userId, 'UserIdentity');
            $selfieSaved = $this->saveImageAndThumbnail($request->file('selfie_image'), false, $userId, 'UserIdentity');

            $frontPath = public_path('files/UserIdentity/' . $userId . '/' . $frontSaved['image']);
            $backPath = public_path('files/UserIdentity/' . $userId . '/' . $backSaved['image']);
            $selfiePath = public_path('files/UserIdentity/' . $userId . '/' . $selfieSaved['image']);

            // Call Gemini Verification Service
            $result = $this->geminiService->verifyIdentity($frontPath, $backPath, $selfiePath);

            if (!$result['success']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['rejection_reason_arabic'] ?? 'فشل التحقق من الهوية.',
                    'data' => [
                        'verification_status' => 'failed',
                        'rejection_reason' => $result['rejection_reason_arabic'] ?? 'فشل التحقق الذكي.',
                    ]
                ], 400);
            }

            // Determine final database status
            // Any status other than 'passed' (e.g. 'failed', 'suspected') translates to 'failed' on the client side
            $dbStatus = ($result['overall_status'] === 'passed') ? 'verified' : 'failed';
            $rejectionReason = ($dbStatus === 'failed') ? $result['rejection_reason_arabic'] : null;

            // Save verification result in DB
            $identity = UserIdentity::create([
                'user_id' => $userId,
                'id_number' => $result['extracted_id_number'] ?? $request->id_number,
                'front_image' => $frontSaved['image'],
                'back_image' => $backSaved['image'],
                'selfie_image' => $selfieSaved['image'],
                'status' => $dbStatus,
                'ai_face_similarity' => $result['face_similarity_score'] ?? null,
                'ai_rejection_reason' => $rejectionReason,
                'ai_verification_report' => $result,
                'ai_raw_response' => $result['raw_response'] ?? null,
            ]);

            return response()->json([
                'status' => ($dbStatus === 'verified'),
                'message' => ($dbStatus === 'verified') ? 'تم توثيق حسابك بنجاح!' : 'لم يتم قبول المستندات: ' . $rejectionReason,
                'data' => [
                    'verification_status' => $dbStatus,
                    'rejection_reason' => $rejectionReason ?? '',
                    'face_similarity_score' => $result['face_similarity_score'] ?? 0,
                    'extracted_name' => $result['extracted_full_name'] ?? '',
                    'extracted_id_number' => $result['extracted_id_number'] ?? '',
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Controller Identity Verification Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في السيرفر أثناء معالجة المستندات: ' . $e->getMessage(),
                'data' => [
                    'verification_status' => 'failed',
                    'rejection_reason' => 'حدث خطأ داخلي في خادم معالجة الهويات.',
                ]
            ], 500);
        }
    }

    /**
     * Retrieve the current identity verification status for the user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function identityStatus(Request $request)
    {
        try {
            $user = Auth::user();
            $identity = UserIdentity::where('user_id', $user->id)->first();

            if (!$identity) {
                return response()->json([
                    'status' => true,
                    'message' => 'no_identity_uploaded',
                    'data' => [
                        'verification_status' => 'unverified',
                        'rejection_reason' => '',
                    ]
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'identity_status_retrieved',
                'data' => [
                    'verification_status' => $identity->status, // 'pending', 'verified', 'failed'
                    'rejection_reason' => $identity->ai_rejection_reason ?? '',
                    'id_number' => $identity->id_number ?? '',
                    'face_similarity_score' => $identity->ai_face_similarity ?? 0,
                ],
                'ai_verification_report' => $identity->ai_verification_report,
            ]);

        } catch (\Exception $e) {
            Log::error('Controller Identity Status Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ في الخادم.',
                'data' => [
                    'verification_status' => 'failed',
                    'rejection_reason' => 'خطأ داخلي.',
                ]
            ], 500);
        }
    }
}
