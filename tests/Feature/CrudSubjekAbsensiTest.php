<?php

namespace Tests\Feature;

use App\Models\KpiPeriod;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudSubjekAbsensiTest extends TestCase
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

        $response = $this->actingAs($super_admin)->post(route('admin.presence-scopes.store'), [
            "name" => "Incididunt enim qui",
        ]);

        $response->assertRedirect(route('admin.presence-scopes.index'));
    }

    public function test_read()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->get(route('admin.presence-scopes.index'));

        $response->assertStatus(200);
    }

    public function test_update()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.presence-scopes.store'), [
            "name" => "Incididunt enim qui"
        ]);
        $response->assertRedirect(route('admin.presence-scopes.index'));

        $kpi = Subject::first();
        $response2 = $this->actingAs($super_admin)->patch(route('admin.presence-scopes.update', $kpi->id), [
            "name" => "Testing"
        ]);
        $response2->assertRedirect(route('admin.presence-scopes.index'));
    }

    public function test_delete()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.presence-scopes.store'), [
            "name" => "Incididunt enim qui",
        ]);
        $response->assertRedirect(route('admin.presence-scopes.index'));

        $kpi = Subject::first();
        $response2 = $this->actingAs($super_admin)->delete(route('admin.presence-scopes.destroy', $kpi->id));
        $response2->assertRedirect(route('admin.presence-scopes.index'));
    }
}