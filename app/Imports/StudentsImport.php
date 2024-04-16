<?php

namespace App\Imports;

use App\Models\Major;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;


class StudentsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // $fails_nim = [];
        // $fails_name = [];
        $raw_majors = Major::all();
        $majors = [];
        foreach ($raw_majors as $raw_major) {
            $majors[$raw_major->major] = $raw_major->id;
        }

        foreach ($rows as $key => $row) {
            // skrip first row
            if ($key > 0 && $row[0] != null) {
                $name = $row[1];
                $nim = $row[0];
                $password = $row[2];
                $semester = $row[3];
                $major = $row[4];
                DB::beginTransaction();
                try {
                    $student = User::create([
                        'name' => $name,
                        'email' => null,
                        'password' => Hash::make($password),
                        'identifier' => 'nim',
                        'identifier_number' => $nim,
                        'email_verified_at' => now()
                    ]);
                    $student->hasMajor()->create([
                        'semester' => $semester,
                        'major_id' => $majors[$major]
                    ]);
                    $student->syncRoles(['mahasiswa']);
                    DB::commit();
                } catch (\Throwable $th) {
                    // throw $th;
                    // Log::debug($th->getMessage());
                    // array_push($fails_name, $name);
                    // array_push($fails_nim, $nim);
                    DB::rollBack();
                }
            }
        }
    }
}
