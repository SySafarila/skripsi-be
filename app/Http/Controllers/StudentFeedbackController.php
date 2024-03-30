<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\FeedbackQuestion;
use App\Models\KpiPeriod;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentFeedbackController extends Controller
{
    public function index()
    {
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
        $questions = FeedbackQuestion::where('type', 'mahasiswa-to-dosen')->orderBy('question', 'asc')->get();
        $n = 1;
        // return $sent_feedbacks;
        return view('students.courses', compact('active_kpi', 'user', 'semester', 'sent_feedbacks', 'courses', 'n', 'questions'));
    }

    public function feedback($course_id)
    {
        $active_kpi = KpiPeriod::where('is_active', true)->first();
        $user = Auth::user();
        $semester = $user->hasMajor->semester;
        $major_id = $user->hasMajor->major_id;
        $course = Course::with('user')->where('major_id', $major_id)->where('semester', $semester)->where('id', $course_id)->first();
        $questions = FeedbackQuestion::with(['responses' => function ($q) use ($active_kpi, $user, $course_id) {
            return $q->where('sender_id', $user->id)->where('kpi_period_id', $active_kpi->id)->where('course_id', $course_id)->get();
        }])->where('type', 'mahasiswa-to-dosen')->orderBy('question', 'asc')->get();
        // return $questions;
        $n = 1;
        return view('students.feedback', compact('course', 'questions', 'n'));
    }

    public function store(Request $request, $course_id)
    {
        $request->validate([
            'question_ids.*' => ['required', 'exists:feedback_questions,id'],
            'messages.*' => ['required', 'string'],
            'points.*' => ['required', 'numeric', 'in:1,2,3,4,5'],
            'questions.*' => ['required', 'string']
        ]);
        // return $request;
        $active_kpi = KpiPeriod::where('is_active', true)->firstOrFail();
        $user = Auth::user();
        $semester = $user->hasMajor->semester;
        $major_id = $user->hasMajor->major_id;
        // $courses = $user->hasMajor->major->courses()->where('semester', $semester)->orderBy('name', 'asc')->get();
        $courses = Course::with('user')->where('major_id', $major_id)->where('semester', $semester)->get();
        $course_ids = $courses->pluck('id')->toArray();
        $course = Course::where('id', $course_id)->firstOrFail();

        if (!in_array($course_id, $course_ids)) {
            return abort(400, 'Invalid request');
        }

        $feedbacks = [];

        foreach ($request->question_ids as $key => $question_id) {
            array_push($feedbacks, [
                'user_id' => $course->user_id,
                'sender_id' => $user->id,
                'kpi_period_id' => $active_kpi->id,
                'feedback_question_id' => $question_id,
                'course_id' => $course->id,
                'question' => $request->questions[$key],
                'point' => $request->points[$key],
                'message' => $request->messages[$key],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }


        DB::beginTransaction();
        try {
            UserFeedback::where('sender_id', $user->id)->where('course_id', $course_id)->where('kpi_period_id', $active_kpi->id)->delete();
            DB::table('user_feedback')->insert($feedbacks);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
        }

        return back();
    }
}
