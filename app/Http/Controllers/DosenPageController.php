<?php

namespace App\Http\Controllers;

use App\Models\KpiPeriod;
use App\Models\Point;
use App\Models\User;
use App\Models\UserFeedback;
use App\Models\UserPresence;
use App\Models\UsersHasSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DosenPageController extends Controller
{
    public function profile()
    {
        $kpi = KpiPeriod::where('is_active', true)->first();
        $user = User::with('subjects.subject', 'presences')->where('id', request()->user()->id)->first();
        $presences = $user->presences()->where('kpi_period_id', $kpi->id)->get();
        $point = Point::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->first();
        return view('home', compact('kpi', 'user', 'presences', 'point'));
    }

    public function presence(Request $request)
    {
        $request->validate([
            'kpi_period_id' => ['required', 'exists:kpi_periods,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'control' => ['required', 'string', 'in:+,-']
        ]);
        $kpi = KpiPeriod::findOrFail($request->kpi_period_id);

        if (now() < $kpi->start_date) {
            return abort(400, 'Belum mulai');
        }
        if (now() > $kpi->end_date) {
            return abort(400, 'Kadaluarsa');
        }

        DB::beginTransaction();
        try {
            if ($request->control == '+') {
                // add presence
                // check quota
                $checkQuota = UsersHasSubject::where('user_id', $request->user()->id)->where('subject_id', $request->subject_id)->first();

                // count presences
                $checkPresences = UserPresence::where('user_id', $request->user()->id)->where('subject_id', $request->subject_id)->where('kpi_period_id', $request->kpi_period_id)->get();
                if ($checkPresences->count() < $checkQuota->quota) {
                    UserPresence::create([
                        'user_id' => request()->user()->id,
                        'kpi_period_id' => $request->kpi_period_id,
                        'subject_id' => $request->subject_id,
                        'status' => 'hadir',
                    ]);
                }
            } else {
                // delete presence
                $presence = UserPresence::where('user_id', $request->user()->id)->where('subject_id', $request->subject_id)->where('kpi_period_id', $request->kpi_period_id)->first();
                if ($presence) {
                    $presence->delete();
                }
            }

            $this->setPoint($kpi, $request->user());

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('DosenPageController Error: . ' . $th->getMessage());
            //throw $th;
        }

        return back();
    }

    private function setPoint($kpi, $user)
    {
        // set points
        $checkPoints = Point::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->first();
        $quotas = UsersHasSubject::where('user_id', $user->id)->get()->pluck('quota')->toArray();

        // presences point
        $quota = array_sum($quotas);

        // survey points
        $surveyPoints = UserFeedback::where('kpi_period_id', $kpi->id)->where('user_id', $user->id)->get()->pluck('point')->toArray();
        $surveyPoint = array_sum($surveyPoints);

        // presence points
        $presencePoints = UserPresence::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->get()->count();
        if (!$checkPoints) {
            $point = Point::create([
                'user_id' => $user->id,
                'kpi_period_id' => $kpi->id,
                'points' => (($presencePoints * 100) / $quota) + $surveyPoint
            ]);
        } else {
            $point = Point::where('user_id', $user->id)->update([
                'points' => (($presencePoints * 100) / $quota) + $surveyPoint
            ]);
        }

        return $point;
    }
}
