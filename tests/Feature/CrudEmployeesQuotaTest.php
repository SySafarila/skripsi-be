<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\KpiPeriod;
use App\Models\Subject;
use App\Models\User;
use App\Models\UsersHasSubject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudEmployeesQuotaTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_kuota_absensi_create()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response1 = $this->actingAs($admin)->get(route('admin.employees-presence-quota.create'));
        $response1->assertStatus(200);

        $response = $this->actingAs($admin)->post(route('admin.employees-presence-quota.store'), [
            'user_id' => 3, // dosen
            'subject_id' => 1, // kalkulus 1
            'quota' => 16
        ]);

        $response->assertRedirect(route('admin.employees-presence-quota.index'));
    }

    public function test_kuota_absensi_read()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->get(route('admin.employees-presence-quota.index'));

        $response->assertStatus(200);
    }

    public function test_kuota_absensi_update()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.employees-presence-quota.store'), [
            'user_id' => 3, // dosen
            'subject_id' => 1, // kalkulus 1
            'quota' => 16
        ]);
        $response->assertRedirect(route('admin.employees-presence-quota.index'));

        $userHasSubject = UsersHasSubject::where('user_id', 3)->where('subject_id', 1)->where('quota', 16)->first();
        $response1 = $this->actingAs($admin)->get(route('admin.employees-presence-quota.edit', $userHasSubject->id));
        $response1->assertStatus(200);
        $response2 = $this->actingAs($admin)->patch(route('admin.employees-presence-quota.update', $userHasSubject->id), [
            'user_id' => 3, // dosen
            'subject_id' => 1, // kalkulus 1
            'quota' => 20
        ]);
        $response2->assertRedirect(route('admin.employees-presence-quota.index'));
    }

    public function test_kuota_absensi_delete()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.employees-presence-quota.store'), [
            'user_id' => 3, // dosen
            'subject_id' => 1, // kalkulus 1
            'quota' => 16
        ]);
        $response->assertRedirect(route('admin.employees-presence-quota.index'));

        $userHasSubject = UsersHasSubject::where('user_id', 3)->where('subject_id', 1)->where('quota', 16)->first();
        $response2 = $this->actingAs($admin)->delete(route('admin.employees-presence-quota.destroy', $userHasSubject->id));
        $response2->assertRedirect(route('admin.employees-presence-quota.index'));
    }
}
