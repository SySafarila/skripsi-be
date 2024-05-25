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
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.kpi.store'), [
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
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->get(route('admin.kpi.index'));

        $response->assertStatus(200);
    }

    public function test_kpi_update()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.kpi.store'), [
            "title" => "Incididunt enim qui",
            "start_date" => "2024-05-23",
            "end_date" => "2024-05-31",
            "is_active" => "1",
            "receive_feedback" => "0"
        ]);
        $response->assertRedirect(route('admin.kpi.index'));

        $kpi = KpiPeriod::first();
        $response2 = $this->actingAs($super_admin)->patch(route('admin.kpi.update', $kpi->id), [
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
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.kpi.store'), [
            "title" => "Incididunt enim qui",
            "start_date" => "2024-05-23",
            "end_date" => "2024-05-31",
            "is_active" => "1",
            "receive_feedback" => "0"
        ]);
        $response->assertRedirect(route('admin.kpi.index'));

        $kpi = KpiPeriod::first();
        $response2 = $this->actingAs($super_admin)->delete(route('admin.kpi.destroy', $kpi->id));
        $response2->assertRedirect(route('admin.kpi.index'));
    }
}
