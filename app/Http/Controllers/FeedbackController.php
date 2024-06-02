<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\FeedbackQuestion;
use App\Models\KpiPeriod;
use App\Models\User;
use App\Models\UserFeedback;
// use App\Models\KpiPeriod;
// use App\Models\Subject;
// use Carbon\Carbon;
use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;
// use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:feedbacks-create')->only(['create', 'store']);
        $this->middleware('can:feedbacks-read')->only('index');
        $this->middleware('can:feedbacks-update')->only(['edit', 'update']);
        $this->middleware('can:feedbacks-delete')->only(['destroy', 'massDestroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = UserFeedback::with(['user', 'sender', 'course', 'tendik_position']);
            if (request()->user_id) {
                $model->where('user_id', request()->user_id);
            }
            if (request()->question_id) {
                $model->where('feedback_question_id', request()->question_id);
            }
            if (request()->kpi_period_id) {
                $model->where('kpi_period_id', request()->kpi_period_id);
            }
            if (request()->course_id) {
                $model->where('course_id', request()->course_id);
            }
            return DataTables::of($model)
                ->addColumn('options', 'admin.feedbacks.datatables.options')
                ->addColumn('for', function($query) {
                    if ($query->user_id) {
                        return $query->user->name;
                    }
                    return $query->tendik_position->division;
                })
                ->addColumn('course_name', function($query) {
                    if ($query->user_id) {
                        return $query->course->name;
                    }
                    return '-';
                })
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }

        $users = User::role('dosen')->orderBy('name')->get();
        $questions = FeedbackQuestion::orderBy('question')->get();
        $kpis = KpiPeriod::orderBy('start_date')->get();
        $courses = Course::orderBy('name')->get();

        return view('admin.feedbacks.index', compact('users', 'questions', 'kpis', 'courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.feedbacks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => ['required', 'string', "unique:user_feedback,question"],
            'type' => ['required', 'string', 'max:255', 'in:mahasiswa-to-dosen']
        ]);

        UserFeedback::create([
            'question' => $request->question,
            'type' => $request->type
        ]);

        return redirect()->route('admin.feedbacks.index')->with('success', 'Umpan Balik berhasil dibuat !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = UserFeedback::findOrFail($id);

        return view('admin.feedbacks.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => ['required', 'string', "unique:user_feedback,question,$id"],
            'type' => ['required', 'string', 'max:255', 'in:mahasiswa-to-dosen']
        ]);

        $question = UserFeedback::findOrFail($id);

        $question->update([
            'question' => $request->question,
            'type' => $request->type
        ]);

        return redirect()->route('admin.feedbacks.index')->with('success', 'Umpan Balik diperbarui !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UserFeedback::destroy($id);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.feedbacks.index')->with('status', 'Permission deleted !');
    }

    public function massDestroy(Request $request)
    {
        $arr = explode(',', $request->ids);

        // foreach ($arr as $data) {
        // UserFeedback::destroy($data);
        // }

        UserFeedback::destroy($arr);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.feedbacks.index')->with('status', 'Bulk delete success');
    }
}
