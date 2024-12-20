<?php

namespace App\Http\Controllers;

use App\Models\FeedbackQuestion;
use App\Models\TendikPosition;
// use App\Models\KpiPeriod;
// use App\Models\Subject;
// use Carbon\Carbon;
use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;
// use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class FeedbackQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:feedback-questions-create')->only(['create', 'store']);
        $this->middleware('can:feedback-questions-read')->only('index');
        $this->middleware('can:feedback-questions-update')->only(['edit', 'update']);
        $this->middleware('can:feedback-questions-delete')->only(['destroy', 'massDestroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = FeedbackQuestion::query();

            if (request()->type) {
                $model->where('tendik_position_id', request()->type);
            }

            return DataTables::of($model->with('to'))
                ->addColumn('options', 'admin.feedback_questions.datatables.options')
                ->editColumn('to', function ($model) {
                    if ($model->to->division == 'Edukatif') {
                        return 'Mahasiswa ke Dosen';
                    }
                    return 'Mahasiswa ke ' . $model->to->division;
                })
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }
        $tendik_positions = TendikPosition::orderBy('division', 'asc')->get();

        return view('admin.feedback_questions.index', compact('tendik_positions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tendikPositions = TendikPosition::orderBy('division', 'asc')->get();

        return view('admin.feedback_questions.create', compact('tendikPositions'));
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
            'tendik_position_id' => ['required', 'exists:tendik_positions,id']
        ]);

        FeedbackQuestion::create([
            'question' => $request->question,
            'tendik_position_id' => $request->tendik_position_id
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Umpan Balik berhasil dibuat !');
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
        $question = FeedbackQuestion::findOrFail($id);
        $tendikPositions = TendikPosition::orderBy('division', 'asc')->get();

        return view('admin.feedback_questions.edit', compact('question', 'tendikPositions'));
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
            'tendik_position_id' => ['required', 'exists:tendik_positions,id']
        ]);

        $question = FeedbackQuestion::findOrFail($id);

        $question->update([
            'question' => $request->question,
            'tendik_position_id' => $request->tendik_position_id
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Umpan Balik diperbarui !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FeedbackQuestion::destroy($id);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.questions.index')->with('status', 'Permission deleted !');
    }

    public function massDestroy(Request $request)
    {
        $arr = explode(',', $request->ids);

        // foreach ($arr as $data) {
        // FeedbackQuestion::destroy($data);
        // }

        FeedbackQuestion::destroy($arr);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.questions.index')->with('status', 'Bulk delete success');
    }
}
