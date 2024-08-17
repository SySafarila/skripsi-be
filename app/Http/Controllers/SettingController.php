<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:general-settings');
    }

    public function index()
    {
        $reload = false;
        $image_presence = Setting::where('key', 'image_presence')->first();
        $min_presence_percentage = Setting::where('key', 'min_presence_percentage')->first();
        $min_average_feedback = Setting::where('key', 'min_average_feedback')->first();

        if (!$image_presence) {
            Setting::create([
                'key' => 'image_presence',
                'value' => 'false'
            ]);
            $reload = true;
        }

        if (!$min_presence_percentage) {
            Setting::create([
                'key' => 'min_presence_percentage',
                'value' => '80'
            ]);
            $reload = true;
        }

        if (!$min_average_feedback) {
            Setting::create([
                'key' => 'min_average_feedback',
                'value' => '4.0'
            ]);
            $reload = true;
        }

        if ($reload === true) {
            return redirect()->route('admin.settings.index');
        }
        return view('admin.settings.index', compact('image_presence', 'min_average_feedback', 'min_presence_percentage'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image_presence' => ['required', 'boolean'],
            'semester_settings' => ['required', 'string', 'in:+,-,n'],
            'min_presence_percentage' => ['required', 'numeric'],
            'min_average_feedback' => ['required', 'numeric']
        ]);

        DB::beginTransaction();
        try {
            $image_presence = Setting::where('key', 'image_presence')->first();
            $min_presence_percentage = Setting::where('key', 'min_presence_percentage')->first();
            $min_average_feedback = Setting::where('key', 'min_average_feedback')->first();

            $image_presence->update([
                'value' => $request->image_presence === '1' ? 'true' : 'false'
            ]);

            $min_average_feedback->update([
                'value' => $request->min_average_feedback
            ]);

            $min_presence_percentage->update([
                'value' => $request->min_presence_percentage
            ]);

            switch ($request->semester_settings) {
                case '+':
                    DB::table('user_has_majors')->increment('semester');
                    break;

                case '-':
                    DB::table('user_has_majors')->decrement('semester');
                    break;

                default:
                    # code...
                    break;
            }



            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return back()->with('success', 'Settings updated');
    }
}
