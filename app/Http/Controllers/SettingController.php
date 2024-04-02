<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:general-settings');
    }

    public function index() {
        $reload = false;
        $image_presence = Setting::where('key', 'image_presence')->first();

        if (!$image_presence) {
            Setting::create([
                'key' => 'image_presence',
                'value' => 'false'
            ]);
            $reload = true;
        }

        if ($reload === true) {
            return redirect()->route('admin.settings.index');
        }
        return view('admin.settings.index', compact('image_presence'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'image_presence' => ['required', 'boolean']
        ]);

        $image_presence = Setting::where('key', 'image_presence')->first();
        $image_presence->update([
            'value' => $request->image_presence === '1' ? 'true' : 'false'
        ]);

        return back()->with('success', 'Settings updated');
    }
}
