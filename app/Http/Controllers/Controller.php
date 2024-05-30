<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\UserFeedback;
use App\Models\UserPresence;
use App\Models\UsersHasSubject;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function setPoint($kpi, $user)
    {
        DB::beginTransaction();
        try {
            // set points
            $checkPoints = Point::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->first();
            $quotas = UsersHasSubject::where('user_id', $user->id)->get()->pluck('quota')->toArray();

            // presences point
            $quota = array_sum($quotas);

            // survey points
            $feedbackPointsArr = UserFeedback::where('kpi_period_id', $kpi->id)->where('user_id', $user->id)->get();
            if ($user->hasRole('tendik')) {
                if (!$user->position->points->where('kpi_period_id', $kpi->id)->first()) {
                    Point::create([
                        'tendik_position_id' => $user->position->id,
                        'kpi_period_id' => $kpi->id,
                        'points' => 0,
                        'presence_points' =>  0,
                        'feedback_points' =>  0
                    ]);
                }
                $resultFeedbackPoints = $user->position->points->where('kpi_period_id', $kpi->id)->first();
            } else {
                $feedbackPoints = array_sum($feedbackPointsArr->pluck('point')->toArray());
                if ($feedbackPoints == 0) {
                    $resultFeedbackPoints = 0;
                } else {
                    $resultFeedbackPoints = $feedbackPoints / $feedbackPointsArr->count();
                }
            }

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

            DB::commit();
            return $point;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function setPointNonEdu($kpi, $tendik_position)
    {
        DB::beginTransaction();
        try {
            // set points
            $checkTendikPoint = Point::where('tendik_position_id', $tendik_position->id)->where('kpi_period_id', $kpi->id)->first();

            // users
            $users = $tendik_position->users;
            $user_ids = $users->pluck('id');
            $user_points = Point::whereIn('user_id', $user_ids)->where('kpi_period_id', $kpi->id)->get();
            $presence_points = $user_points->count() == 0 ? 0 : array_sum($user_points->pluck('presence_points')->toArray()) / $user_points->count();

            // survey points
            $feedbackPointsArr = UserFeedback::where('kpi_period_id', $kpi->id)->where('tendik_position_id', $tendik_position->id)->get();
            $feedbackPoints = array_sum($feedbackPointsArr->pluck('point')->toArray());
            $resultFeedbackPoints = $feedbackPoints == 0 ? 0 : $feedbackPoints / $feedbackPointsArr->count();

            if (!$checkTendikPoint) {
                $point = Point::create([
                    'tendik_position_id' => $tendik_position->id,
                    'kpi_period_id' => $kpi->id,
                    'points' => $resultFeedbackPoints,
                    'presence_points' => $presence_points ?? 0,
                    'feedback_points' => $resultFeedbackPoints ?? 0
                ]);
            } else {
                $point = Point::where('tendik_position_id', $tendik_position->id)->where('kpi_period_id', $kpi->id)->update([
                    'points' => $resultFeedbackPoints,
                    'presence_points' => $presence_points ?? 0,
                    'feedback_points' => $resultFeedbackPoints ?? 0
                ]);
            }

            Point::where('kpi_period_id', $kpi->id)->whereIn('user_id', $user_ids->toArray())->update([
                'feedback_points' => $resultFeedbackPoints ?? 0
            ]);

            foreach ($user_points as $key => $user_point) {
                // $user_point->points = $user_point->presence_points + $user_point->feedback_points;
                // $user_point->update();
                $user_point->update([
                    'points' => $user_point->presence_points + $user_point->feedback_points
                ]);
            }

            DB::commit();
            return $point;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
