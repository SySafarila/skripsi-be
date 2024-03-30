<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\KpiPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentFeedbackController extends Controller
{
    public function index() {
        return view('students.index');
    }
    public function courses()
    {
        $active_kpi = KpiPeriod::where('is_active', true)->first();
        $user = Auth::user();
        $semester = $user->hasMajor->semester;
        $major_id = $user->hasMajor->major_id;
        // $courses = $user->hasMajor->major->courses()->where('semester', $semester)->orderBy('name', 'asc')->get();
        $courses = Course::with('user')->where('major_id', $major_id)->where('semester', $semester)->get();
        $course_ids = $courses->pluck('id');
        $sent_feedbacks = $user->sent_feedbacks()->where('kpi_period_id', $active_kpi->id)->whereIn('course_id', $course_ids)->get();
        $n = 1;
        // return $sent_feedbacks;
        return view('students.courses', compact('active_kpi', 'user', 'semester', 'sent_feedbacks', 'courses', 'n'));
    }
}
