<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\FeedbackQuestion;
use App\Models\KpiPeriod;
use App\Models\TendikPosition;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentFeedbackController extends Controller
{
    public function index()
    {
        return view('students.welcome');
    }

    public function profile()
    {
        $courses = Auth::user()->hasMajor->major->courses()->with('user')->where('semester', Auth::user()->hasMajor->semester)->orderBy('name')->get();
        return view('students.profile', compact('courses'));
    }

    public function feedbacks()
    {
        $active_kpi = KpiPeriod::where('is_active', true)->first();
        if (!$active_kpi) {
            abort(404, 'Tidak ditemukan KPI yang aktif');
        }
        $user = Auth::user();
        $semester = $user->hasMajor->semester;
        $major_id = $user->hasMajor->major_id;
        // $courses = $user->hasMajor->major->courses()->where('semester', $semester)->orderBy('name', 'asc')->get();
        $courses = Course::with('user')->where('major_id', $major_id)->where('semester', $semester)->orderBy('name', 'asc')->get();
        $course_ids = $courses->pluck('id');
        $sent_feedbacks = $user->sent_feedbacks()->where('kpi_period_id', $active_kpi->id)->get();
        $eduQuestions = FeedbackQuestion::with('to')->orderBy('question', 'asc')->whereRelation('to', 'division', '=', 'Edukatif')->get();
        $nonEduQuestions = FeedbackQuestion::with('to')->orderBy('question', 'asc')->whereRelation('to', 'division', '!=', 'Edukatif')->get()->groupBy('tendik_position_id');
        $n = 1;
        $nn = 1;
        // return $nonEduQuestions;
        return view('students.courses', compact('active_kpi', 'user', 'semester', 'sent_feedbacks', 'courses', 'n', 'nn', 'eduQuestions', 'nonEduQuestions'));
    }

    public function feedback($course_id)
    {
        $active_kpi = KpiPeriod::where('is_active', true)->first();
        if (!$active_kpi) {
            abort(404, 'Tidak ditemukan KPI yang aktif');
        }
        $user = Auth::user();
        $semester = $user->hasMajor->semester;
        $major_id = $user->hasMajor->major_id;
        $course = Course::with('user')->where('major_id', $major_id)->where('semester', $semester)->where('id', $course_id)->first();
        $questions = FeedbackQuestion::with(['responses' => function ($q) use ($active_kpi, $user, $course_id) {
            return $q->where('sender_id', $user->id)->where('kpi_period_id', $active_kpi->id)->where('course_id', $course_id);
        }])->whereRelation('to', 'division', '=', 'Edukatif')->orderBy('question', 'asc')->get();
        // return $questions;
        $n = 1;
        return view('students.feedback', compact('course', 'questions', 'n', 'active_kpi'));
    }

    public function nonedu_feedback($tendik_position_id)
    {
        $active_kpi = KpiPeriod::where('is_active', true)->first();
        if (!$active_kpi) {
            abort(404, 'Tidak ditemukan KPI yang aktif');
        }
        $user = Auth::user();
        $semester = $user->hasMajor->semester;
        $major_id = $user->hasMajor->major_id;
        $tendik_position = TendikPosition::findOrFail($tendik_position_id);
        // return $tendik_position;
        // $course = Course::with('user')->where('major_id', $major_id)->where('semester', $semester)->where('id', $course_id)->first();
        $questions = FeedbackQuestion::with(['responses' => function ($q) use ($active_kpi, $user) {
            return $q->where('sender_id', $user->id)->where('kpi_period_id', $active_kpi->id);
        }])->whereRelation('to', 'id', '=', $tendik_position->id)->orderBy('question', 'asc')->get();
        // return $questions;
        $n = 1;
        return view('students.feedback', compact('questions', 'n', 'active_kpi', 'tendik_position'));
    }

    public function store(Request $request, $course_id)
    {
        $request->validate([
            'question_ids.*' => ['required', 'exists:feedback_questions,id'],
            'messages.*' => ['required', 'string'],
            'points.*' => ['required', 'numeric', 'in:1,2,3,4,5'],
            'questions.*' => ['required', 'string']
        ]);
        $active_kpi = KpiPeriod::where('is_active', true)->where('receive_feedback', true)->first();
        if (!$active_kpi) {
            abort(404, 'Tidak ditemukan KPI yang aktif atau menerima masukan');
        }
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
            $this->setPoint($active_kpi, $course->user);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('student.courses.index')->with('success', 'Masukan berhasil terkirim.');
    }

    public function nonedu_store(Request $request, $tendik_position_id)
    {
        $request->validate([
            'question_ids.*' => ['required', 'exists:feedback_questions,id'],
            'messages.*' => ['required', 'string'],
            'points.*' => ['required', 'numeric', 'in:1,2,3,4,5'],
            'questions.*' => ['required', 'string']
        ]);
        $tendik_position = TendikPosition::find($tendik_position_id);
        if (!$tendik_position) {
            abort(404, 'Tendik tidak ditemukan');
        }
        $active_kpi = KpiPeriod::where('is_active', true)->where('receive_feedback', true)->first();
        if (!$active_kpi) {
            abort(404, 'Tidak ditemukan KPI yang aktif atau menerima masukan');
        }

        $user = Auth::user();
        $feedbacks = [];

        foreach ($request->question_ids as $key => $question_id) {
            array_push($feedbacks, [
                // 'user_id' => $course->user_id,
                'sender_id' => $user->id,
                'kpi_period_id' => $active_kpi->id,
                'feedback_question_id' => $question_id,
                // 'course_id' => $course->id,
                'tendik_position_id' => $tendik_position_id,
                'question' => $request->questions[$key],
                'point' => $request->points[$key],
                'message' => $request->messages[$key],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        // return $feedbacks;

        DB::beginTransaction();
        try {
            UserFeedback::where('sender_id', $user->id)->where('tendik_position_id', $tendik_position_id)->where('kpi_period_id', $active_kpi->id)->delete();
            DB::table('user_feedback')->insert($feedbacks);
            $this->setPointNonEdu($active_kpi, $tendik_position);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('student.courses.index')->with('success', 'Masukan berhasil terkirim.');
    }
}
