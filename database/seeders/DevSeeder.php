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
        // tendik positions
        TendikPosition::create([
            'division' => 'Edukatif'
        ]);
        TendikPosition::create([
            'division' => 'Layanan Akademik'
        ]);
        TendikPosition::create([
            'division' => 'Layanan Kemahasiswaan'
        ]);
        TendikPosition::create([
            'division' => 'Layanan Pengelolaan Keuangan Maupun Sarana Dan Prasarana'
        ]);

        // users
        $dosen = User::create([
            'name' => 'Dosen',
            'email' => 'dosen@dosen.com',
            'password' => Hash::make('password'),
            'identifier' => 'nidn',
            'identifier_number' => 133,
            'email_verified_at' => now(),
            'tendik_position_id' => 1 // edukatif
        ]);
        $dosen->syncRoles(['dosen']);

        $dosen2 = User::create([
            'name' => 'Dosen 2',
            'email' => 'dosen2@dosen2.com',
            'password' => Hash::make('password'),
            'identifier' => 'nidn',
            'identifier_number' => 123,
            'email_verified_at' => now(),
            'tendik_position_id' => 1 // edukatif
        ]);
        $dosen2->syncRoles(['dosen']);

        $tendik = User::create([
            'name' => 'Tendik 1',
            'email' => 'tendik@tendik.com',
            'password' => Hash::make('password'),
            'identifier' => 'nip',
            'identifier_number' => 1234,
            'email_verified_at' => now(),
            'tendik_position_id' => 2 // bagian keuangan
        ]);
        $tendik->syncRoles(['tendik']);
        $tendik2 = User::create([
            'name' => 'Tendik 2',
            'email' => 'tendik2@tendik2.com',
            'password' => Hash::make('password'),
            'identifier' => 'nip',
            'identifier_number' => 1235,
            'email_verified_at' => now(),
            'tendik_position_id' => 2 // bagian keuangan
        ]);
        $tendik2->syncRoles(['tendik']);
        $tendik3 = User::create([
            'name' => 'Tendik 3',
            'email' => 'tendik3@tendik3.com',
            'password' => Hash::make('password'),
            'identifier' => 'nip',
            'identifier_number' => 1236,
            'email_verified_at' => now(),
            'tendik_position_id' => 3 // bagian lab
        ]);
        $tendik3->syncRoles(['tendik']);

        $mahasiswa = User::create([
            'name' => 'mahasiswa',
            'email' => 'mahasiswa@mahasiswa.com',
            'password' => Hash::make('password'),
            'identifier' => 'nim',
            'identifier_number' => 207200005,
            'email_verified_at' => now()
        ]);
        $mahasiswa->syncRoles(['mahasiswa']);

        $dosen3 = User::create([
            'name' => 'Dosen 3',
            'email' => 'dosen3@dosen3.com',
            'password' => Hash::make('password'),
            'identifier' => 'nidn',
            'identifier_number' => 12333,
            'email_verified_at' => now(),
            'tendik_position_id' => 1 // edukatif
        ]);
        $dosen3->syncRoles(['dosen']);

        $dosen4 = User::create([
            'name' => 'Dosen 4',
            'email' => 'dosen4@dosen4.com',
            'password' => Hash::make('password'),
            'identifier' => 'nidn',
            'identifier_number' => 12344,
            'email_verified_at' => now(),
            'tendik_position_id' => 1 // edukatif
        ]);
        $dosen4->syncRoles(['dosen']);

        $tendik4 = User::create([
            'name' => 'Tendik 4',
            'email' => 'tendik4@tendik4.com',
            'password' => Hash::make('password'),
            'identifier' => 'nip',
            'identifier_number' => 12366,
            'email_verified_at' => now(),
            'tendik_position_id' => 3 // bagian lab
        ]);
        $tendik4->syncRoles(['tendik']);

        $mahasiswa2 = User::create([
            'name' => 'mahasiswa 2',
            'email' => 'mahasiswa2@mahasiswa2.com',
            'password' => Hash::make('password'),
            'identifier' => 'nim',
            'identifier_number' => 207200006,
            'email_verified_at' => now()
        ]);
        $mahasiswa2->syncRoles(['mahasiswa']);

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
            'user_id' => $mahasiswa->id,
            'major_id' => 1,
            'semester' => 2
        ]);

        // feedback question
        FeedbackQuestion::create([
            'question' => 'Menjelaskan RPS Mata Kuliah yang diampu dan membuat kesepakatan mengenai kehadiran dan proses belajar dalam 1 semester',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Tingkat Kehadiran Dosen dalam perkuliahan',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Keteraturan dan ketertiban penyelenggaraan perkuliahan',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Kedisiplinan dan kepatuhan terhadap aturan akademik',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Menciptakan suasana lingkungan belajar yang nyaman',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Penguasaan media teknologi pembelajaran',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Penggunaan hasil-hasil penelitian untuk meningkatkan kualitas perkuliahaan',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Pelibatan mahasiswa dalam penelitian/kajian dan atau pengembangan/rekayasa/desain yang dilakukan dosen',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Kewibawaan sebagai pribadi dosen',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Menjadi contoh dalam bersikap dan berperilaku',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Kemampuan mengendalikan dri dari berbagai situasi dan kondisi',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Saran dan Masukan',
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        FeedbackQuestion::create([
            'question' => 'Kemampuan Bidang Akademik, Fakultas, Program Studi dan staff administrasi dalam memberikan layanan akademik (pengurusan KRS, KHS, dokumen seminar, Ijazah, dokumen ujian akhir, surat menyurat dll).',
            'tendik_position_id' => 2 // mahasiswa to layanan akademik
        ]);
        FeedbackQuestion::create([
            'question' => 'Kemauan/kesediaan Bidang Akademik, Fakultas,  Program Studi dan staff administrasi dalam memberikan layanan akademik (pengurusan KRS, KHS, dokumen seminar, Ijazah, dokumen ujian akhir, surat menyurat dll)  dengan cepat.',
            'tendik_position_id' => 2 // mahasiswa to layanan akademik
        ]);
        FeedbackQuestion::create([
            'question' => 'Bidang Akademik, Fakultas, Program Studi dan staff administrasi dalam memberikan layanan akademik   (pengurusan KRS, KHS, dokumen seminar, Ijazah, dokumen ujian akhir, surat menyurat dll)   sudah sesuai dengan SOP/ketentuan.',
            'tendik_position_id' => 2 // mahasiswa to layanan akademik
        ]);
        FeedbackQuestion::create([
            'question' => 'Kepedulian Bidang Akademik, Fakultas,  Program Studi dan staff administrasi dalam memberikan layanan akademik dengan budaya kerja yang unggul, sopan, ramah dan penuh perhatian.',
            'tendik_position_id' => 2 // mahasiswa to layanan akademik
        ]);
        FeedbackQuestion::create([
            'question' => 'Layanan akademik sudah menggunakan sarana dan prasarana (termasuk IT) yang memudahkan bagi pengguna.',
            'tendik_position_id' => 2 // mahasiswa to layanan akademik
        ]);
        FeedbackQuestion::create([
            'question' => 'Kemampuan layanan di bidang kemahasiswaan dalam memberikan layanan dan bimbingan di bidang kreativitas ilmiah (PKM, lomba karya tulis ilmiah, Penelitian yang melibatkan mahasiswa dan sejenisnya)',
            'tendik_position_id' => 3 // mahasiswa to Layanan kemahasiswaan
        ]);
        FeedbackQuestion::create([
            'question' => 'Kemauan/kesediaan layana di bidang kemahasiswaan dalam memberikan layanan dan bimbingan di bidang kreativitas ilmiah (PKM, lomba karya tulis ilmiah, Penelitian yang melibatkan mahasiswa dan sejenisnya)',
            'tendik_position_id' => 3 // mahasiswa to Layanan kemahasiswaan
        ]);
        FeedbackQuestion::create([
            'question' => 'Bidang kemahasiswaan dalam memberikan layanan dan bimbingan di bidang kreativitas ilmiah   (PKM, lomba karya tulis ilmiah, Penelitian yang melibatkan mahasiswa dan sejenisnya) sudah sesuai dengan SOP/ketentuan',
            'tendik_position_id' => 3 // mahasiswa to Layanan kemahasiswaan
        ]);
        FeedbackQuestion::create([
            'question' => 'Kepedulian layanan di bidang kemahasiswaan dalam memberikan perhatian pada layanan dan bimbingan di bidang kreativitas ilmiah   (PKM, lomba karya tulis ilmiah, Penelitian yang melibatkan mahasiswa dan sejenisnya)',
            'tendik_position_id' => 3 // mahasiswa to Layanan kemahasiswaan
        ]);
        FeedbackQuestion::create([
            'question' => 'Layanan kemahasiswaan sudah menggunakan sarana dan prasarana (termasuk IT) yang memudahkan bagi pengguna.',
            'tendik_position_id' => 3 // mahasiswa to Layanan kemahasiswaan
        ]);
        FeedbackQuestion::create([
            'question' => 'Kemampuan tenaga kependidikan, dan pengelola dalam memberikan pelayanan keuangan, sarana dan prasarana.',
            'tendik_position_id' => 4 // mahasiswa to LAYANAN PENGELOLAAN KEUANGAN MAUPUN SARANA DAN PRASARANA
        ]);
        FeedbackQuestion::create([
            'question' => 'Kemauan tenaga kependidikan, dan pengelola dalam membantu mahasiswa untuk memberikan jasa dengan cepat dalam proses layanan keuangan, sarana dan prasarana.',
            'tendik_position_id' => 4 // mahasiswa to LAYANAN PENGELOLAAN KEUANGAN MAUPUN SARANA DAN PRASARANA
        ]);
        FeedbackQuestion::create([
            'question' => 'Kemampuan tenaga kependidikan, dan pengelola untuk memberi keyakinan kepada mahasiswa bahwa pelayanan keuangan, sarana dan prasarana yang diberikan telah sesuai dengan ketentuan.',
            'tendik_position_id' => 4 // mahasiswa to LAYANAN PENGELOLAAN KEUANGAN MAUPUN SARANA DAN PRASARANA
        ]);
        FeedbackQuestion::create([
            'question' => 'Kesediaan/kepedulian tenaga kependidikan, dan pengelola untuk memberi perhatian kepada mahasiswa dalam memberikan layanan keuangan, sarana dan prasarana.',
            'tendik_position_id' => 4 // mahasiswa to LAYANAN PENGELOLAAN KEUANGAN MAUPUN SARANA DAN PRASARANA
        ]);
        FeedbackQuestion::create([
            'question' => 'Layanan keuangan, sarana dan prasarana. sudah menggunakan suatu sistem (termasuk IT) yang memudahkan bagi pengguna.',
            'tendik_position_id' => 4 // mahasiswa to LAYANAN PENGELOLAAN KEUANGAN MAUPUN SARANA DAN PRASARANA
        ]);
    }
}
