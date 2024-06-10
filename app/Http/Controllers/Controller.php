<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\UserFeedback;
use App\Models\UserPresence;
use App\Models\UsersHasSubject;
use Exception;
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
            // check user point
            $userPoint = Point::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->first();
            if (!$userPoint) {
                $userPoint = Point::create([
                    'user_id' => $user->id,
                    'kpi_period_id' => $kpi->id,
                    'points' => 0,
                    'presence_points' => 0,
                    'feedback_points' => 0
                ]);
            }
            $quotaArray = UsersHasSubject::where('user_id', $user->id)->get()->pluck('quota')->toArray();

            // quota sum
            $quotaSum = array_sum($quotaArray);

            // get feedback points
            $userFeedbackPoint = UserFeedback::where('kpi_period_id', $kpi->id)->where('user_id', $user->id)->get();
            $feedbackPointSum = array_sum($userFeedbackPoint->pluck('point')->toArray());
            if ($feedbackPointSum == 0) {
                $calculated_fedback_points = 0;
            } else {
                $calculated_fedback_points = $feedbackPointSum / $userFeedbackPoint->count();
            }

            // if tendik
            if ($user->hasRole('tendik')) {

                if ($user->position) {
                    $tendikPositionPoint = $user->position->points->where('kpi_period_id', $kpi->id)->first();
                    if (!$tendikPositionPoint) {
                        Point::create([
                            'tendik_position_id' => $user->position->id,
                            'kpi_period_id' => $kpi->id,
                            'points' => 0,
                            'presence_points' =>  0,
                            'feedback_points' =>  0
                        ]);
                    }
                }
            }

            // presence points
            $userPresencePoints = UserPresence::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->get()->count();
            $multiplyUserPresencePoints = $userPresencePoints * 100;

            if ($multiplyUserPresencePoints == 0) {
                $calculatedPresencePoints = 0;
            } else {
                $calculatedPresencePoints = $multiplyUserPresencePoints / $quotaSum;
            }

            // update user point
            $userPoint->update([
                'points' => $calculatedPresencePoints + $calculated_fedback_points,
                'presence_points' => $calculatedPresencePoints,
                'feedback_points' => $calculated_fedback_points
            ]);

            DB::commit();
            return $userPoint;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function setPointNonEdu($kpi, $tendik_position)
    {
        DB::beginTransaction();
        try {
            // check tendik position point
            $tendikPositionPoint = Point::where('tendik_position_id', $tendik_position->id)->where('kpi_period_id', $kpi->id)->first();
            if (!$tendikPositionPoint) {
                $tendikPositionPoint = Point::create([
                    'tendik_position_id' => $tendik_position->id,
                    'kpi_period_id' => $kpi->id,
                    'points' => 0,
                    'presence_points' => 0,
                    'feedback_points' => 0
                ]);
            }

            // get tendik users
            $users = $tendik_position->users;
            $user_ids = $users->pluck('id')->toArray();
            $users_points = Point::whereIn('user_id', $user_ids)->where('kpi_period_id', $kpi->id)->get();

            $presence_points = $users_points->pluck('presence_points')->toArray();
            if ($users_points->count() == 0) {
                $calculated_presence_points = 0;
            } else {
                $calculated_presence_points = array_sum($presence_points);
            }

            // feedback points
            $feedbackPointsArr = UserFeedback::where('kpi_period_id', $kpi->id)->where('tendik_position_id', $tendik_position->id)->get();
            $feedbackPointSum = array_sum($feedbackPointsArr->pluck('point')->toArray());

            if ($feedbackPointSum == 0) {
                $calculated_feedback_points = 0;
            } else {
                $calculated_feedback_points = $feedbackPointSum / $feedbackPointsArr->count();
            }

            // update tendik point
            $tendikPositionPoint->update([
                'points' => $calculated_feedback_points + $calculated_presence_points,
                'presence_points' => $calculated_presence_points,
                'feedback_points' => $calculated_feedback_points
            ]);

            // update feedback points for tendik users
            Point::where('kpi_period_id', $kpi->id)->whereIn('user_id', $user_ids)->update([
                'feedback_points' => $calculated_feedback_points
            ]);

            $user_points = Point::whereIn('user_id', $user_ids)->where('kpi_period_id', $kpi->id)->get();
            foreach ($user_points as $key => $user_point) {
                $user_point->update([
                    'points' => $user_point->presence_points + $user_point->feedback_points
                ]);
            }

            DB::commit();
            return $tendikPositionPoint;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function kpi_date_validator($kpi)
    {
        if (now() < $kpi->start_date) {
            throw new Exception('Periode KPI belum dimulai');
            // return abort(404, 'Periode KPI belum dimulai');
        }
        if (now() > $kpi->end_date) {
            throw new Exception('Periode KPI telah berakhir');
            // return abort(404, 'Periode KPI telah kadaluarsa');
        }
        return true;
    }
}
