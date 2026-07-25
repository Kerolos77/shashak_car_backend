<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use App\Models\AdminUserAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewManagementController extends Controller
{
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $toUserId = $review->to_user_id;
        $fromUserId = $review->from_user_id;
        $ratingVal = $review->rating;

        // Delete the review
        $review->delete();

        // Recalculate rating average for to_user_id
        if ($toUserId) {
            $newAvg = Review::where('to_user_id', $toUserId)->avg('rating');
            $newAvg = $newAvg ? round($newAvg, 2) : 5.00;

            $user = User::find($toUserId);
            if ($user) {
                $user->rating = $newAvg;
                $user->save();

                if ($user->profile) {
                    $user->profile->rating = $newAvg;
                    $user->profile->save();
                }
            }

            // Log admin audit action
            AdminUserAuditLog::create([
                'admin_id' => Auth::guard('admin')->id() ?? Auth::id(),
                'user_id'  => $toUserId,
                'action'   => 'delete_review',
                'notes'    => "تم حذف تقييم بقيمة ({$ratingVal} نجوم) وإعادة حساب متوسط التقييم إلى ({$newAvg}).",
            ]);
        }

        return redirect()->back()->with('success', __('تم حذف التقييم وإعادة حساب متوسط التقييم بنجاح.'));
    }
}
