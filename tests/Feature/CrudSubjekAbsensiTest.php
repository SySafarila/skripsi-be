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
    public function test_lingkup_absensi_create()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response1 = $this->actingAs($admin)->get(route('admin.presence-scopes.create'));
        $response1->assertStatus(200);

        $response = $this->actingAs($admin)->post(route('admin.presence-scopes.store'), [
            "name" => "Incididunt enim qui",
        ]);

        $response->assertRedirect(route('admin.presence-scopes.index'));
    }

    public function test_lingkup_absensi_read()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->get(route('admin.presence-scopes.index'));

        $response->assertStatus(200);
    }

    public function test_lingkup_absensi_update()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.presence-scopes.store'), [
            "name" => "Incididunt enim qui"
        ]);
        $response->assertRedirect(route('admin.presence-scopes.index'));

        $subject = Subject::first();
        $response1 = $this->actingAs($admin)->get(route('admin.presence-scopes.edit', $subject->id));
        $response1->assertStatus(200);
        $response2 = $this->actingAs($admin)->patch(route('admin.presence-scopes.update', $subject->id), [
            "name" => "Testing"
        ]);
        $response2->assertRedirect(route('admin.presence-scopes.index'));
    }

    public function test_lingkup_absensi_delete()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.presence-scopes.store'), [
            "name" => "Incididunt enim qui",
        ]);
        $response->assertRedirect(route('admin.presence-scopes.index'));

        $subject = Subject::first();
        $response2 = $this->actingAs($admin)->delete(route('admin.presence-scopes.destroy', $subject->id));
        $response2->assertRedirect(route('admin.presence-scopes.index'));
    }
}
