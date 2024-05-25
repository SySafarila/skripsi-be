<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\FeedbackQuestion;
use App\Models\KpiPeriod;
use App\Models\Major;
use App\Models\Point;
use App\Models\TendikPosition;
use App\Models\User;
use App\Models\UserHasMajor;
use App\Models\UsersHasSubject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // users
        $dosen = User::create([
            'name' => 'Dosen',
            'email' => 'dosen@dosen.com',
            'password' => Hash::make('password'),
            'identifier' => 'nidn',
            'identifier_number' => 133,
            'email_verified_at' => now()
        ]);
        $dosen->syncRoles(['dosen']);

        $dosen2 = User::create([
            'name' => 'Dosen 2',
            'email' => 'dosen2@dosen2.com',
            'password' => Hash::make('password'),
            'identifier' => 'nidn',
            'identifier_number' => 123,
            'email_verified_at' => now()
        ]);
        $dosen2->syncRoles(['dosen']);

        $tendik = User::create([
            'name' => 'Tendik',
            'email' => 'tendik@tendik.com',
            'password' => Hash::make('password'),
            'identifier' => 'nip',
            'identifier_number' => 1234,
            'email_verified_at' => now()
        ]);
        $tendik->syncRoles(['tendik']);

        $mahasiswa = User::create([
            'name' => 'mahasiswa',
            'email' => 'mahasiswa@mahasiswa.com',
            'password' => Hash::make('password'),
            'identifier' => 'nim',
            'identifier_number' => 207,
            'email_verified_at' => now()
        ]);
        $mahasiswa->syncRoles(['mahasiswa']);

        // mata kuliah
        $subjects = [
            [
                'name' => 'Kalkulus 1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Kalkulus 2',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mobile Programming 1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mobile Programming 2',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Staff',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('subjects')->insert($subjects);

        // kpi
        $kpi = KpiPeriod::create([
            'title' => 'Testing KPI',
            'start_date' => now('Asia/Jakarta'),
            'end_date' => now('Asia/Jakarta')->addMonths(6),
            'is_active' => true,
            'receive_feedback' => true
        ]);

        $users = User::role(['dosen', 'tendik'])->get();
        $arr = [];
        foreach ($users as $user) {
            $check = Point::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->first();
            if (!$check) {
                $time = now();
                array_push($arr, [
                    'user_id' => $user->id,
                    'kpi_period_id' => $kpi->id,
                    'points' => 0,
                    'presence_points' => 0,
                    'feedback_points' => 0,
                    'created_at' => $time,
                    'updated_at' => $time
                ]);
            }
        }
        DB::table('points')->insert($arr);

        // feedback question
        FeedbackQuestion::create([
            'question' => 'lorem ipsum dolor',
            'type' => 'mahasiswa-to-dosen'
        ]);
        FeedbackQuestion::create([
            'question' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'type' => 'mahasiswa-to-dosen'
        ]);
        FeedbackQuestion::create([
            'question' => 'Lorem  consectetur adipisicing elit.',
            'type' => 'mahasiswa-to-dosen'
        ]);

        // kuota absensi
        UsersHasSubject::create([
            'user_id' => 3,
            'subject_id' => 1,
            'quota' => 16
        ]);
        UsersHasSubject::create([
            'user_id' => 3,
            'subject_id' => 2,
            'quota' => 16
        ]);
        UsersHasSubject::create([
            'user_id' => 5,
            'subject_id' => 5,
            'quota' => 18
        ]);
        UsersHasSubject::create([
            'user_id' => 4,
            'subject_id' => 3,
            'quota' => 18
        ]);
        UsersHasSubject::create([
            'user_id' => 4,
            'subject_id' => 4,
            'quota' => 18
        ]);
        UsersHasSubject::create([
            'user_id' => 6,
            'subject_id' => 5,
            'quota' => 22
        ]);

        // jurusan
        Major::create([
            'major' => 'Teknik Informatika'
        ]);
        Major::create([
            'major' => 'Manajemen Informatika'
        ]);
        Major::create([
            'major' => 'Manajemen'
        ]);
        Major::create([
            'major' => 'Akuntansi'
        ]);
        Major::create([
            'major' => 'Sastra Inggris'
        ]);
        Major::create([
            'major' => 'Ilmu Komunikasi'
        ]);

        // mata kuliah
        Course::create([
            'name' => 'Kalkulus 1',
            'user_id' => 3,
            'semester' => 1,
            'major_id' => 1
        ]);

        Course::create([
            'name' => 'Kalkulus 2',
            'user_id' => 3,
            'semester' => 2,
            'major_id' => 1
        ]);

        Course::create([
            'name' => 'Mobile Programming 1',
            'user_id' => 4,
            'semester' => 1,
            'major_id' => 1
        ]);

        Course::create([
            'major_id' => 1,
            'name' => 'Mobile Programming 2',
            'user_id' => 4,
            'semester' => 2
        ]);

        // assign mahasiswa
        UserHasMajor::create([
            'user_id' => 6,
            'major_id' => 1,
            'semester' => 2
        ]);

        // tendik positions
        TendikPosition::create([
            'name' => 'Dosen',
            'division' => 'Edukatif'
        ]);

        TendikPosition::create([
            'name' => 'Kepala Bagian',
            'division' => 'Bagian Keuangan'
        ]);
    }
}
