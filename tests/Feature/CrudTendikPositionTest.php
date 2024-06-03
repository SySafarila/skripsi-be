<?php

namespace Tests\Feature;

use App\Models\KpiPeriod;
use App\Models\Subject;
use App\Models\TendikPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudTendikPositionTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_jabatan_tendik_create()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response1 = $this->actingAs($super_admin)->get(route('admin.tendik-positions.create'));
        $response1->assertStatus(200);

        $response = $this->actingAs($super_admin)->post(route('admin.tendik-positions.store'), [
            "name" => "Incididunt enim qui",
            "division" => "division"
        ]);

        $response->assertRedirect(route('admin.tendik-positions.index'));
    }

    public function test_jabatan_tendik_read()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->get(route('admin.tendik-positions.index'));

        $response->assertStatus(200);
    }

    public function test_jabatan_tendik_update()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.tendik-positions.store'), [
            "name" => "Incididunt enim qui",
            "division" => "division"
        ]);
        $response->assertRedirect(route('admin.tendik-positions.index'));

        $position = TendikPosition::first();
        $response1 = $this->actingAs($super_admin)->get(route('admin.tendik-positions.edit', $position->id));
        $response1->assertStatus(200);
        $response2 = $this->actingAs($super_admin)->patch(route('admin.tendik-positions.update', $position->id), [
            "name" => "Testing",
            "division" => "division"
        ]);
        $response2->assertRedirect(route('admin.tendik-positions.index'));
    }

    public function test_jabatan_tendik_delete()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.tendik-positions.store'), [
            "name" => "Incididunt enim qui",
            "division" => "division"
        ]);
        $response->assertRedirect(route('admin.tendik-positions.index'));

        $position = TendikPosition::first();
        $response2 = $this->actingAs($super_admin)->delete(route('admin.tendik-positions.destroy', $position->id));
        $response2->assertRedirect(route('admin.tendik-positions.index'));
    }
}
