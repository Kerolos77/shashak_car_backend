<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'model' => 'required|string',
        ]);

        $modelClass = $this->resolveModel($request->model);

        if (!$modelClass) {
            return response()->json([
                'status' => false,
                'message' => __('Model not found.')
            ], 404);
        }

        $instance = $modelClass::find($request->id);

        if (!$instance) {
            return response()->json([
                'status' => false,
                'message' => __('Record not found.')
            ], 404);
        }

        if (!property_exists($instance, 'is_active') && !isset($instance->is_active)) {
            return response()->json([
                'status' => false,
                'message' => __('Status field not available.')
            ], 400);
        }

        $instance->is_active = !$instance->is_active;
        $instance->save();

        return response()->json([
            'status' => true,
            'is_active' => (bool)$instance->is_active,  // Ensure boolean
            'message' => __('Status updated successfully.')
        ]);
    }

    protected function resolveModel($model)
    {
        $models = [
            'user' => \App\Models\User::class,
            'appointmentresearcher' => \App\Models\AppointmentResearcher::class,
            'researcher' => \App\Models\Researcher::class,
            'researcherslider' => \App\Models\ResearcherSlider::class,
            'researcherfaq' => \App\Models\ResearcherFaq::class,

            // Add more as needed
        ];

        return $models[strtolower($model)] ?? null;
    }
}
