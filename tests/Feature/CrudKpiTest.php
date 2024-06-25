<?php

namespace Tests\Feature;

use App\Models\KpiPeriod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudKpiTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_kpi_create()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response1 = $this->actingAs($admin)->get(route('admin.kpi.create'));
        $response1->assertStatus(200);

        $response = $this->actingAs($admin)->post(route('admin.kpi.store'), [
            "title" => "Incididunt enim qui",
            "start_date" => "2024-05-23",
            "end_date" => "2024-05-31",
            "is_active" => "1",
            "receive_feedback" => "0"
        ]);

        $response->assertRedirect(route('admin.kpi.index'));
    }

    public function test_kpi_read()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->get(route('admin.kpi.index'));

        $response->assertStatus(200);
    }

    public function test_kpi_update()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.kpi.store'), [
            "title" => "Incididunt enim qui",
            "start_date" => "2024-05-23",
            "end_date" => "2024-05-31",
            "is_active" => "1",
            "receive_feedback" => "0"
        ]);
        $response->assertRedirect(route('admin.kpi.index'));

        $kpi = KpiPeriod::first();
        $response1 = $this->actingAs($admin)->get(route('admin.kpi.edit', $kpi->id));
        $response1->assertStatus(200);
        $response2 = $this->actingAs($admin)->patch(route('admin.kpi.update', $kpi->id), [
            "title" => "Testing",
            "start_date" => "2024-05-23",
            "end_date" => "2024-05-31",
            "is_active" => "1",
            "receive_feedback" => "0"
        ]);
        $response2->assertRedirect(route('admin.kpi.index'));
    }

    public function test_kpi_delete()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.kpi.store'), [
            "title" => "Incididunt enim qui",
            "start_date" => "2024-05-23",
            "end_date" => "2024-05-31",
            "is_active" => "1",
            "receive_feedback" => "0"
        ]);
        $response->assertRedirect(route('admin.kpi.index'));

        $kpi = KpiPeriod::first();
        $response2 = $this->actingAs($admin)->delete(route('admin.kpi.destroy', $kpi->id));
        $response2->assertRedirect(route('admin.kpi.index'));
    }
}
