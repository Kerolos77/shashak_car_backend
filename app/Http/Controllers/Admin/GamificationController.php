<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class GamificationController extends BaseController
{
    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }

    public function index(Request $request = null)
    {
        $row = $this->model->first();
        $pageTitle = trans('cruds.gamification.title');
        return view('admin.gamification.edit', compact('row', 'pageTitle'));
    }

    public function update(Request $request)
    {
        $row = $this->model->firstOrFail();
        $data = $request->only([
            'points_per_trip_user',
            'points_per_trip_driver',
            'points_visa_bonus_user',
            'points_visa_bonus_driver',
            'points_five_star_bonus_driver',
            'points_cancel_penalty_user',
            'points_cancel_penalty_driver'
        ]);

        $row->update($data);

        return redirect()->back()->with('success', trans('global.update_success') ?? 'Updated successfully');
    }
}
