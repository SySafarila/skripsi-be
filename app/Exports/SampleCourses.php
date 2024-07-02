<?php

namespace App\Exports;

use App\Models\Major;
// use App\Models\TendikPosition;
use App\Models\User;
use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class SampleCourses implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $users = User::role('dosen')->orderBy('name', 'asc')->get();
        $majors = Major::orderBy('major', 'asc')->get();

        if (count($users) > count($majors)) {
            $loop_sample = $users;
        } else {
            $loop_sample = $majors;
        }

        return view('excels.courses', ['users' => $users, 'majors' => $majors, 'loop_sample' => $loop_sample]);
    }
}
