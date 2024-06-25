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
        $admin = User::where('email', 'admin@admin.com')->first();

        $response1 = $this->actingAs($admin)->get(route('admin.tendik-positions.create'));
        $response1->assertStatus(200);

        $response = $this->actingAs($admin)->post(route('admin.tendik-positions.store'), [
            "name" => "Incididunt enim qui",
            "division" => "division"
        ]);

        $response->assertRedirect(route('admin.tendik-positions.index'));
    }

    public function test_jabatan_tendik_read()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->get(route('admin.tendik-positions.index'));

        $response->assertStatus(200);
    }

    public function test_jabatan_tendik_update()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.tendik-positions.store'), [
            "name" => "Incididunt enim qui",
            "division" => "division"
        ]);
        $response->assertRedirect(route('admin.tendik-positions.index'));

        $position = TendikPosition::first();
        $response1 = $this->actingAs($admin)->get(route('admin.tendik-positions.edit', $position->id));
        $response1->assertStatus(200);
        $response2 = $this->actingAs($admin)->patch(route('admin.tendik-positions.update', $position->id), [
            "name" => "Testing",
            "division" => "division"
        ]);
        $response2->assertRedirect(route('admin.tendik-positions.index'));
    }

    public function test_jabatan_tendik_delete()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.tendik-positions.store'), [
            "name" => "Incididunt enim qui",
            "division" => "division"
        ]);
        $response->assertRedirect(route('admin.tendik-positions.index'));

        $position = TendikPosition::first();
        $response2 = $this->actingAs($admin)->delete(route('admin.tendik-positions.destroy', $position->id));
        $response2->assertRedirect(route('admin.tendik-positions.index'));
    }
}
