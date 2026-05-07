<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Setting;
use App\Models\Page;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    /**
     * Get enabled FAQs
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $faqs = Faq::where('enable', true)
                ->select('id', 'title', 'description', 'created_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $faqs,
                'message' => 'Active FAQs retrieved successfully'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve FAQs',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get contact information
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactUs()
    {
        try {
            $setting = Setting::first();

            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contact information not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => $setting,
                'message' => 'Contact information retrieved successfully'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contact information',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get page content
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pages(Request $request)
    {
        try {
            $request->validate([
                'slug' => 'sometimes|string',
                'id' => 'sometimes|integer|exists:pages,id',
                'locale' => 'sometimes|in:en,ar'
            ]);

            $locale = $request->locale ?? 'en';
            $contentField = 'content_' . $locale;

            $query = Page::query();

            if ($request->has('slug')) {
                $query->where('slug', $request->slug);
            } elseif ($request->has('id')) {
                $query->where('id', $request->id);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Either slug or id parameter is required'
                ], Response::HTTP_BAD_REQUEST);
            }

            $page = $query->select([
                'id',
                'name',
                'slug',
                $contentField . ' as content',
                'created_at',
                'updated_at'
            ])->first();

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => $page,
                'message' => 'Page content retrieved successfully'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve page content',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get percentage increase values
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function percentageIncrease()
    {
        try {
            $setting = Setting::first();

            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Settings not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $activeType = $setting->active_type ?? 'percentage_increase';

            // Return 'increase' array if activeType is 'increase', otherwise fallback to percentage array
            $data = ($activeType === 'increase') ? ($setting->increase ?? []) : ($setting->percentage_increase ?? []);

            return response()->json([
                'success' => true,
                'data' => $data,
                'active_type' => $activeType,
                'message' => 'Values retrieved successfully'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve percentage increase values',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Handle write us contact form submission
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function write_us(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|max:255',
                'description' => 'required|string|max:1000'
            ]);

            // Here you can add logic to save the contact form data
            // For example, save to a contact_messages table or send an email

            // For now, we'll just return a success response
            // You can implement email sending or database storage here

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully. We will get back to you soon!',
                'data' => [
                    'email' => $request->email,
                    'message' => 'Contact form submitted successfully'
                ]
            ], Response::HTTP_OK);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update gamification settings
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateGamificationSettings(Request $request)
    {
        try {
            $validated = $request->validate([
                'points_driver_per_trip' => 'required|integer|min:0',
                'points_driver_visa_bonus' => 'required|integer|min:0',
                'points_driver_five_star' => 'required|integer|min:0',
                'points_driver_cancel_penalty' => 'required|integer|min:0',
                'points_user_per_trip' => 'required|integer|min:0',
                'points_user_visa_bonus' => 'required|integer|min:0',
                'points_user_cancel_penalty' => 'required|integer|min:0',
            ]);

            $setting = Setting::first();
            if (!$setting) {
                $setting = new Setting();
            }

            $setting->fill($validated);
            $setting->save();

            return response()->json([
                'success' => true,
                'data' => $setting,
                'message' => 'Gamification settings updated successfully'
            ], Response::HTTP_OK);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}