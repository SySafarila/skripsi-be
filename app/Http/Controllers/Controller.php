<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\UserFeedback;
use App\Models\UserPresence;
use App\Models\UsersHasSubject;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function setPoint($kpi, $user)
    {
        // set points
        $checkPoints = Point::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->first();
        $quotas = UsersHasSubject::where('user_id', $user->id)->get()->pluck('quota')->toArray();

        // presences point
        $quota = array_sum($quotas);

        // survey points
        $feedbackPointsArr = UserFeedback::where('kpi_period_id', $kpi->id)->where('user_id', $user->id)->get();
        $feedbackPoints = array_sum($feedbackPointsArr->pluck('point')->toArray());
        $resultFeedbackPoints = $feedbackPoints == 0 ? 0 : ($feedbackPoints * 100) / $feedbackPointsArr->count();

        // presence points
        $presencePoints = UserPresence::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->get()->count();
        $resultX = $presencePoints * 100;
        $resultPresencePoints = $resultX == 0 ? 0 : $resultX / $quota;
        if (!$checkPoints) {
            $point = Point::create([
                'user_id' => $user->id,
                'kpi_period_id' => $kpi->id,
                'points' => $resultPresencePoints + $resultFeedbackPoints,
                'presence_points' => $resultPresencePoints ?? 0,
                'feedback_points' => $resultFeedbackPoints ?? 0
            ]);
        } else {
            $point = Point::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->update([
                'points' => $resultPresencePoints + $resultFeedbackPoints,
                'presence_points' => $resultPresencePoints ?? 0,
                'feedback_points' => $resultFeedbackPoints ?? 0
            ]);
        }

        return $point;
    }
}
