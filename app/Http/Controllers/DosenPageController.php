<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\KpiPeriod;
// use App\Models\Point;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserFeedback;
// use App\Models\UserFeedback;
use App\Models\UserPresence;
use App\Models\UsersHasSubject;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DosenPageController extends Controller
{
    public function welcome()
    {
        return view('employees.welcome');
    }

    public function profile()
    {
        $kpi = KpiPeriod::where('is_active', true)->first();
        $points = KpiPeriod::with(['points' => function ($q) {
            return $q->where('user_id', request()->user()->id);
        }])->orderBy('start_date', 'desc')->limit(5)->get();
        $achievements = Achievement::where('user_id', request()->user()->id)->where('position', '<=', 5)->latest()->get();
        $user = Auth::user();
        $roles = $user->roles->pluck('name');

        return view('employees.profile', compact('kpi', 'achievements', 'points', 'roles', 'user'));
    }

    public function my_feedback()
    {
        $feedbacks = UserFeedback::where('user_id', request()->user()->id)->latest()->get()->groupBy('question');
        return view('employees.feedback', compact('feedbacks'));
    }

    public function profile_show(Request $request, $id)
    {
        $user = User::with('roles')->where('id', $id)->firstOrFail();
        $roles = $user->roles->pluck('name');
        // $kpi = KpiPeriod::where('is_active', true)->first();
        // $user = User::with('subjects.subject')->where('id', $user->id)->first();
        $points = KpiPeriod::with(['points' => function ($q) use ($user) {
            return $q->where('user_id', $user->id);
        }])->orderBy('start_date', 'desc')->limit(5)->get();
        $achievements = Achievement::where('user_id', $user->id)->where('position', '<=', 5)->latest()->get();
        return view('employees.profile-show', compact('user', 'achievements', 'points', 'roles'));
    }

    public function subject($subject_id)
    {
        $valid_kpi = true;
        $kpi = KpiPeriod::where('is_active', true)->first();
        if (!$kpi) {
            abort(404, 'KPI Not found');
        }
        try {
            $this->kpi_date_validator($kpi);
            $valid_kpi = true;
        } catch (\Throwable $th) {
            //throw $th;
            $valid_kpi = $th->getMessage();
        }
        // $user = User::with('presences')->where('id', request()->user()->id)->first();
        $subject = Subject::findOrFail($subject_id);
        // $presences = $user->presences()->where('kpi_period_id', $kpi->id)->where('subject_id', $subject_id)->orderBy('created_at', 'desc')->get();
        // $userHasSubjectId = $user->subjects()->where('subject_id', $subject->id)->firstOrFail()->id;
        $image_presence_setting = Setting::where('key', 'image_presence')->first();
        $presences = Auth::user()->presences()->where('kpi_period_id', $kpi->id)->where('subject_id', $subject->id)->latest()->get();
        $isTodays = [];
        foreach ($presences as $presence) {
            array_push($isTodays, $this->isToday($presence->created_at));
        }

        return view('employees.presences-show', compact('kpi', 'image_presence_setting', 'subject', 'presences', 'isTodays', 'valid_kpi'));
    }

    private function isToday($date)
    {
        $date = \Carbon\Carbon::parse($date)->format('d/m/Y');
        if ($date == now()->format('d/m/Y')) {
            return true;
        } else {
            return false;
        }
    }

    public function presence_index()
    {
        $kpi = KpiPeriod::where('is_active', true)->first();
        if (!$kpi) {
            abort(404, 'KPI Not found');
        }
        try {
            $this->kpi_date_validator($kpi);
            $valid_kpi = true;
        } catch (\Throwable $th) {
            //throw $th;
            $valid_kpi = $th->getMessage();
        }
        $subjects = Auth::user()->subjects()->with('subject')->get();

        return view('employees.presences', compact('kpi', 'subjects', 'valid_kpi'));
    }

    public function presence(Request $request)
    {
        $request->validate([
            'kpi_period_id' => ['required', 'exists:kpi_periods,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'control' => ['required', 'string', 'in:+,-'],
            'image' => ['nullable', 'file', 'image'],
            // 'users_has_subject_id' => ['required', 'exists:users_has_subjects,id']
        ]);
        $image_presence_setting = Setting::where('key', 'image_presence')->first();

        if ($image_presence_setting->value == 'true') {
            $request->validate([
                'image' => ['required', 'file', 'image'],
            ]);
        }

        $kpi = KpiPeriod::where('is_active', true)->where('id', $request->kpi_period_id)->firstOrFail();

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $this->kpi_date_validator($kpi);
            if ($request->control == '+') {
                // add presence
                // check quota
                $checkQuota = UsersHasSubject::where('user_id', $user->id)->where('subject_id', $request->subject_id)->first();

                // count presences
                $checkPresences = UserPresence::where('user_id', $user->id)->where('subject_id', $request->subject_id)->where('kpi_period_id', $request->kpi_period_id)->get();
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
                        'image' => $path,
                        // 'users_has_subject_id' => $request->users_has_subject_id
                    ]);
                }
            } else {
                // delete presence
                $presence = UserPresence::where('user_id', $user->id)->where('subject_id', $request->subject_id)->where('kpi_period_id', $request->kpi_period_id)->where('id', $request->presence_id)->first();
                if ($presence) {
                    if ($presence->image && Storage::disk('public')->exists($presence->image)) {
                        Storage::disk('public')->delete($presence->image);
                    }
                    $presence->delete();
                }
            }

            $this->setPoint($kpi, $user);
            if ($user->position) {
                if ($user->position->division != 'Edukatif') {
                    $this->setPointNonEdu($kpi, $user->position);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        if ($request->control == '+') {
            return back()->with('success', 'Data berhasil disimpan.');
        }
        return back()->with('success', 'Data berhasil dihapus.');
    }
}
