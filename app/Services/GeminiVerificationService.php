<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiVerificationService
{
    /**
     * Verify the front ID, back ID, and selfie using Gemini 1.5 Flash.
     *
     * @param string $frontImagePath
     * @param string $backImagePath
     * @param string $selfieImagePath
     * @return array
     */
    public function verifyIdentity(string $frontImagePath, string $backImagePath, string $selfieImagePath): array
    {
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            Log::error('Gemini Verification failed: GEMINI_API_KEY is not set in .env');
            return [
                'success' => false,
                'overall_status' => 'failed',
                'rejection_reason_arabic' => 'خطأ في إعدادات الخادم للتحقق الذكي (API Key missing).',
                'detailed_report' => 'GEMINI_API_KEY is not configured on the server.',
            ];
        }

        if (!file_exists($frontImagePath) || !file_exists($backImagePath) || !file_exists($selfieImagePath)) {
            Log::error('Gemini Verification failed: One or more image files do not exist', [
                'front' => $frontImagePath,
                'back' => $backImagePath,
                'selfie' => $selfieImagePath,
            ]);
            return [
                'success' => false,
                'overall_status' => 'failed',
                'rejection_reason_arabic' => 'ملفات الصور غير متوفرة على الخادم، يرجى المحاولة مرة أخرى.',
                'detailed_report' => 'One or more source files were not found on the disk.',
            ];
        }

        try {
            $frontBase64 = base64_encode(file_get_contents($frontImagePath));
            $backBase64 = base64_encode(file_get_contents($backImagePath));
            $selfieBase64 = base64_encode(file_get_contents($selfieImagePath));

            $prompt = "You are an automated identity verification AI system. Analyze these 3 images:
1. The front side of an Egyptian National ID Card.
2. The back side of the same Egyptian National ID Card.
3. A selfie of the user.

Your tasks are:
1. Extract the 14-digit National ID number from the front ID card image.
2. Extract the Arabic name from the front ID card image.
3. Compare the front ID card with the back ID card. Verify if they belong to the same physical card (e.g. check if the type of card templates matches, look for MRZ or barcode matching, etc.). Note that in Egyptian ID cards, the 14-digit ID number is on the front, and it is usually encoded in the MRZ/barcode on the back (if present).
4. Perform Face Comparison: Compare the face in the user's selfie with the photo printed on the front of the ID card. Determine if they are the same person and calculate a similarity score (0 to 100).
5. Check for AI Generation/Manipulation & Spoofing:
   - For the selfie: Ensure it is a real, live photo of a person (not a photo of a screen, printed paper, or AI-generated/modified photo/deepfake).
   - For the ID front and back: Check if they are photos of actual physical plastic ID cards (not digital screen photos, scans, printed paper copies, or template-generated fake IDs).
6. Set 'overall_status' to 'passed' if the face matches (similarity >= 70%), front/back match, the cards are real physical cards, the selfie is live, and no AI manipulation or screen-photo spoofing is detected. Otherwise, set it to 'failed' (or 'suspected' if there is minor quality/lighting issue but details match).
7. If 'overall_status' is 'failed' or 'suspected', write a clear, friendly, and specific explanation in Arabic in 'rejection_reason_arabic' so the user can see it on their screen and fix the issue (e.g. 'يرجى التقاط صورة سيلفي حية، ولا يسمح بتصوير شاشات أخرى' or 'وجه البطاقة وخلفها لا ينتميان لنفس البطاقة'). Keep it concise (1-2 sentences).";

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inlineData' => [
                                    'mimeType' => 'image/jpeg',
                                    'data' => $frontBase64
                                ]
                            ],
                            [
                                'inlineData' => [
                                    'mimeType' => 'image/jpeg',
                                    'data' => $backBase64
                                ]
                            ],
                            [
                                'inlineData' => [
                                    'mimeType' => 'image/jpeg',
                                    'data' => $selfieBase64
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'responseSchema' => [
                        'type' => 'OBJECT',
                        'properties' => [
                            'extracted_id_number' => ['type' => 'STRING', 'description' => 'The 14-digit Egyptian National ID number'],
                            'extracted_full_name' => ['type' => 'STRING', 'description' => 'Full name in Arabic from the ID card'],
                            'face_matched' => ['type' => 'BOOLEAN'],
                            'face_similarity_score' => ['type' => 'INTEGER'],
                            'front_back_matched' => ['type' => 'BOOLEAN'],
                            'is_real_selfie' => ['type' => 'BOOLEAN'],
                            'is_real_id_front' => ['type' => 'BOOLEAN'],
                            'is_real_id_back' => ['type' => 'BOOLEAN'],
                            'ai_generated_or_modified_detected' => ['type' => 'BOOLEAN'],
                            'overall_status' => [
                                'type' => 'STRING',
                                'enum' => ['passed', 'failed', 'suspected']
                            ],
                            'rejection_reason_arabic' => ['type' => 'STRING', 'description' => 'Explanation of the issue in Arabic for the user screen if status is failed/suspected, empty otherwise'],
                            'detailed_report' => ['type' => 'STRING', 'description' => 'Detailed notes of the checks']
                        ],
                        'required' => [
                            'extracted_id_number',
                            'extracted_full_name',
                            'face_matched',
                            'face_similarity_score',
                            'front_back_matched',
                            'is_real_selfie',
                            'is_real_id_front',
                            'is_real_id_back',
                            'ai_generated_or_modified_detected',
                            'overall_status',
                            'rejection_reason_arabic',
                            'detailed_report'
                        ]
                    ]
                ]
            ];

            // Send request to Gemini API (1.5 Flash)
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(60) // High timeout for heavy multi-image processing
              ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey, $payload);

            if ($response->failed()) {
                Log::error('Gemini API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [
                    'success' => false,
                    'overall_status' => 'failed',
                    'rejection_reason_arabic' => 'فشل الاتصال بخدمة التحقق الذكي، يرجى المحاولة لاحقاً.',
                    'detailed_report' => 'Gemini API returned error status: ' . $response->status() . '. Body: ' . $response->body(),
                ];
            }

            $responseBody = $response->json();
            $textResponse = $responseBody['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (empty($textResponse)) {
                Log::error('Gemini API returned empty text response', ['response' => $responseBody]);
                return [
                    'success' => false,
                    'overall_status' => 'failed',
                    'rejection_reason_arabic' => 'لم نتمكن من تحليل استجابة نظام التحقق الذكي.',
                    'detailed_report' => 'No content text parts returned from Gemini API.',
                ];
            }

            $parsedData = json_decode($textResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Gemini JSON response', ['text' => $textResponse]);
                return [
                    'success' => false,
                    'overall_status' => 'failed',
                    'rejection_reason_arabic' => 'حدث خطأ أثناء معالجة بيانات التحقق الذكي.',
                    'detailed_report' => 'Failed to parse JSON text from Gemini: ' . json_last_error_msg(),
                ];
            }

            // Return the parsed data along with raw response
            $parsedData['success'] = true;
            $parsedData['raw_response'] = $responseBody;

            return $parsedData;

        } catch (\Exception $e) {
            Log::error('Exception during Gemini identity verification: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'overall_status' => 'failed',
                'rejection_reason_arabic' => 'حدث خطأ غير متوقع أثناء معالجة التحقق.',
                'detailed_report' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }
}
