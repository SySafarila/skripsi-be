<?php

namespace App\Http\Controllers;

use App\Models\KpiPeriod;
use App\Models\Point;
use App\Models\Subject;
use App\Models\User;
// use App\Models\UserFeedback;
use App\Models\UserPresence;
use App\Models\UsersHasSubject;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DosenPageController extends Controller
{
    public function profile()
    {
        $kpi = KpiPeriod::where('is_active', true)->first();
        $user = User::with('subjects.subject', 'presences')->where('id', request()->user()->id)->first();
        $presences = $user->presences()->where('kpi_period_id', $kpi->id)->get();
        $point = Point::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->first();
        return view('dosen.profile', compact('kpi', 'user', 'presences', 'point'));
    }

    public function subject($subject_id)
    {
        $kpi = KpiPeriod::where('is_active', true)->first();
        $user = User::with('presences')->where('id', request()->user()->id)->first();
        $subject = Subject::findOrFail($subject_id);
        $presences = $user->presences()->where('kpi_period_id', $kpi->id)->where('subject_id', $subject_id)->orderBy('created_at', 'desc')->get();
        return view('dosen.subject', compact('kpi', 'user', 'presences', 'subject'));
    }

    public function presence(Request $request)
    {
        $request->validate([
            'kpi_period_id' => ['required', 'exists:kpi_periods,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'control' => ['required', 'string', 'in:+,-'],
            'image' => ['nullable', 'file', 'image']
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
                    if ($request->hasFile('image')) {
                        $path = Storage::disk('public')->putFile('presences', new File($request->file('image')), 'public');
                    } else {
                        $path = null;
                    }
                    UserPresence::create([
                        'user_id' => request()->user()->id,
                        'kpi_period_id' => $request->kpi_period_id,
                        'subject_id' => $request->subject_id,
                        'status' => 'hadir',
                        'image' => $path
                    ]);
                }
            } else {
                // delete presence
                $presence = UserPresence::where('user_id', $request->user()->id)->where('subject_id', $request->subject_id)->where('kpi_period_id', $request->kpi_period_id)->where('id', $request->presence_id)->first();
                if ($presence) {
                    if ($presence->image && Storage::disk('public')->exists($presence->image)) {
                        Storage::disk('public')->delete($presence->image);
                    }
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
}
