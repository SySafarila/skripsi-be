<?php

namespace App\Imports;

use App\Models\Major;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;


class EmployeesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $employees_identifier = User::role(['dosen', 'tendik', 'staff'])->get('identifier_number');
        $exists_identifier = [];
        foreach ($employees_identifier as $student_nim) {
            array_push($exists_identifier, $student_nim->identifier_number);
        }

        foreach ($rows as $key => $row) {
            // skrip first row
            if ($key > 0 && $row[0] != null && in_array($row[0], $exists_identifier) == false) {
                $identifier = $row[0];
                $identifier_number = $row[1];
                $name = $row[2];
                $type = $row[3];
                $password = $row[4];
                DB::beginTransaction();
                try {
                    $student = User::create([
                        'name' => $name,
                        'email' => null,
                        'password' => Hash::make($password),
                        'identifier' => $identifier,
                        'identifier_number' => $identifier_number,
                        'email_verified_at' => now()
                    ]);
                    $student->syncRoles([$type]);
                    DB::commit();
                } catch (\Throwable $th) {
                    // throw $th;
                    DB::rollBack();
                }
            }
        }
    }
}
