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

class CrudEmployeesTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.employees.store'), [
            'name' => 'testing',
            'email' => null,
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'dosen',
            'identifier' => 'nidn',
            'identifier_number' => 1234567890,
            'position' => '-'
        ]);

        $response->assertRedirect(route('admin.employees.index'));
    }

    public function test_read()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->get(route('admin.employees.index'));

        $response->assertStatus(200);
    }

    public function test_update()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.employees.store'), [
            'name' => 'testing',
            'email' => null,
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'dosen',
            'identifier' => 'nidn',
            'identifier_number' => 1234567890,
            'position' => '-'
        ]);
        $response->assertRedirect(route('admin.employees.index'));

        $kpi = User::where('name', 'testing')->first();
        $response2 = $this->actingAs($super_admin)->patch(route('admin.employees.update', $kpi->id), [
            'name' => 'testing 2',
            'email' => null,
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'dosen',
            'identifier' => 'nidn',
            'identifier_number' => 1234567890,
            'position' => '-'
        ]);
        $response2->assertRedirect(route('admin.employees.index'));
    }

    public function test_delete()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.employees.store'), [
            'name' => 'testing',
            'email' => null,
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'dosen',
            'identifier' => 'nidn',
            'identifier_number' => 1234567890,
            'position' => '-'
        ]);
        $response->assertRedirect(route('admin.employees.index'));

        $kpi = User::where('name', 'testing')->first();
        $response2 = $this->actingAs($super_admin)->delete(route('admin.employees.destroy', $kpi->id));
        $response2->assertRedirect(route('admin.employees.index'));
    }
}
