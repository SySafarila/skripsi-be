<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\FeedbackQuestion;
use App\Models\KpiPeriod;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EmployeesTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_karyawan_akses_homepage()
    {
        // $this->seed();
        $super_admin = User::where('email', 'dosen@dosen.com')->first();

        $response = $this->actingAs($super_admin)->get(route('employees.welcome'));

        $response->assertStatus(200);
    }

    public function test_karyawan_akses_kehadiran()
    {
        // $this->seed();
        $super_admin = User::where('email', 'dosen@dosen.com')->first();

        $response = $this->actingAs($super_admin)->get(route('employees.presence.index'));

        $response->assertStatus(200);
    }

    public function test_karyawan_akses_leaderboard()
    {
        // $this->seed();
        $super_admin = User::where('email', 'dosen@dosen.com')->first();

        $response = $this->actingAs($super_admin)->get(route('employees.leaderboard.index'));

        $response->assertStatus(200);
    }

    public function test_karyawan_akses_profile()
    {
        // $this->seed();
        $super_admin = User::where('email', 'dosen@dosen.com')->first();

        $response = $this->actingAs($super_admin)->get(route('employees.profile'));

        $response->assertStatus(200);
    }

    public function test_karyawan_akses_profile_lain()
    {
        // $this->seed();
        $super_admin = User::where('email', 'dosen@dosen.com')->first();

        $response = $this->actingAs($super_admin)->get(route('employees.profile', [
            'id' => 5
        ]));

        $response->assertStatus(200);
    }

    public function test_karyawan_akses_isi_absensi()
    {
        // $this->seed();
        $super_admin = User::where('email', 'dosen@dosen.com')->first();
        $tendik = User::where('email', 'tendik@tendik.com')->first();

        $response = $this->actingAs($super_admin)->get(route('employees.presence.show', 1));
        $response->assertStatus(200);

        $response2 = $this->actingAs($super_admin)->post(route('employees.presence.store', 1), [
            "kpi_period_id" => "1",
            "subject_id" => "1",
            "control" => "+"
        ]);
        $response2->assertSessionHas('success');

        $response3 = $this->actingAs($tendik)->get(route('employees.presence.show', 5));
        $response3->assertStatus(200);

        $response4 = $this->actingAs($tendik)->post(route('employees.presence.store', 5), [
            "kpi_period_id" => "1",
            "subject_id" => "5",
            "control" => "+"
        ]);
        $response4->assertSessionHas('success');
    }

    public function test_karyawan_akses_hapus_absensi()
    {
        // $this->seed();
        $super_admin = User::where('email', 'dosen@dosen.com')->first();

        $response = $this->actingAs($super_admin)->get(route('employees.presence.show', 1));
        $response->assertStatus(200);

        $response2 = $this->actingAs($super_admin)->post(route('employees.presence.store', 1), [
            "kpi_period_id" => "1",
            "subject_id" => "1",
            "control" => "+"
        ]);
        $response2->assertSessionHas('success');

        $response3 = $this->actingAs($super_admin)->post(route('employees.presence.store', 1), [
            "kpi_period_id" => "1",
            "subject_id" => "1",
            "presence_id" => "2",
            "control" => "-"
        ]);
        $response3->assertSessionHas('success');
    }
}
