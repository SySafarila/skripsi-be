<?php

namespace App\Exports;

use App\Models\Major;
// use App\Models\TendikPosition;
// use App\Models\User;
use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class SampleStudents implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('excels.students', ['majors' => Major::all()]);
    }
}
