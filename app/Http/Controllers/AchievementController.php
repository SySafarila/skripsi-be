<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\KpiPeriod;
use App\Models\Point;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AchievementController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:achievements-create')->only(['create', 'store', 'generate']);
        $this->middleware('can:achievements-read')->only(['index']);
        $this->middleware('can:achievements-update')->only(['edit', 'update']);
        $this->middleware('can:achievements-delete')->only(['destroy', 'massDestroy']);
    }

    public function generate($kpi_id)
    {
        $kpi = KpiPeriod::where('id', $kpi_id)->firstOrFail();
        $points = Point::with('user.roles')->where('kpi_period_id', $kpi_id)->where('tendik_position_id', null)->orderBy('points', 'desc')->orderBy('updated_at', 'asc')->get();

        $dosens = [];
        $tendiks = [];

        foreach ($points as $index => $point) {
            if ($point->points > 0) {
                // dosens
                if ($point->user->roles[0]->name == 'dosen') {
                    array_push($dosens, $point->user_id);
                }
                // tendiks
                if ($point->user->roles[0]->name == 'tendik') {
                    array_push($tendiks, $point->user_id);
                }
            }
        }

        // dosen
        $dosenArr = [];
        foreach ($dosens as $index => $dosen) {
            $index = $index + 1;
            array_push($dosenArr, [
                'user_id' => $dosen,
                'kpi_period_id' => $kpi_id,
                'title' => "Dosen #$index periode " . Carbon::parse($kpi->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($kpi->end_date)->format('d/m/Y'),
                'position' => $index,
                'created_at' => $kpi->end_date,
                'updated_at' => $kpi->end_date
            ]);
        }

        // tendik
        $tendikArr = [];
        foreach ($tendiks as $index => $tendik) {
            $index = $index + 1;
            array_push($tendikArr, [
                'user_id' => $tendik,
                'kpi_period_id' => $kpi_id,
                'title' => "Tendik #$index periode " . Carbon::parse($kpi->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($kpi->end_date)->format('d/m/Y'),
                'position' => $index,
                'created_at' => $kpi->end_date,
                'updated_at' => $kpi->end_date
            ]);
        }

        DB::beginTransaction();
        try {
            Achievement::where('kpi_period_id', $kpi_id)->delete();
            // DB::table('achievements')->insert($employees); // pegawai
            DB::table('achievements')->insert($dosenArr); // dosen
            DB::table('achievements')->insert($tendikArr); // tendik
            // DB::table('achievements')->insert($staffArr); // staff
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }

        return back()->with('success', 'Pencapaian berhasil digenerate!');
    }

    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(Achievement::query()->with('user', 'kpi'))
                ->addColumn('options', 'admin.achievements.datatables.options')
                ->editColumn('user_name', function ($model) {
                    return $model->user ? $model->user->name : '-';
                })
                ->editColumn('kpi_title', function ($model) {
                    return $model->kpi ? $model->kpi->title : '-';
                })
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }

        return view('admin.achievements.index');
    }

    public function destroy($id)
    {
        Achievement::destroy($id);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.achievements.index')->with('status', 'Permission deleted !');
    }

    public function massDestroy(Request $request)
    {
        $arr = explode(',', $request->ids);

        // foreach ($arr as $data) {
        // Achievement::destroy($data);
        // }

        Achievement::destroy($arr);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.achievements.index')->with('status', 'Bulk delete success');
    }
}
