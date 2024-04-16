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
        foreach ($rows as $key => $row) {
            // skrip first row
            if ($key > 0 && $row[0] != null) {
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
                    // Log::debug($th->getMessage());
                    // array_push($fails_name, $name);
                    // array_push($fails_nim, $nim);
                    DB::rollBack();
                }
            }
        }
    }
}
