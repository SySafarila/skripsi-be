<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Course;
use App\Models\KpiPeriod;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudAchievementTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_create()
    // {
    //     // $this->seed();
    //     $super_admin = User::where('email', 'super.admin@admin.com')->first();

    //     $response = $this->actingAs($super_admin)->post(route('admin.achievements.store'), [
    //         'name' => "Testing",
    //         'user_id' => 3, // dosen
    //         'semester' => 2,
    //         'major_id' => 1 // teknik informatika
    //     ]);

    //     $response->assertRedirect(route('admin.achievements.index'));
    // }

    public function test_read()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->get(route('admin.achievements.index'));

        $response->assertStatus(200);
    }

    // public function test_update()
    // {
    //     // $this->seed();
    //     $super_admin = User::where('email', 'super.admin@admin.com')->first();

    //     $response = $this->actingAs($super_admin)->post(route('admin.achievements.store'), [
    //         'name' => "Testing",
    //         'user_id' => 3, // dosen
    //         'semester' => 2,
    //         'major_id' => 1 // teknik informatika
    //     ]);
    //     $response->assertRedirect(route('admin.achievements.index'));

    //     $achievement = Achievement::where('name', 'Testing')->first();
    //     $response2 = $this->actingAs($super_admin)->patch(route('admin.achievements.update', $achievement->id), [
    //         'name' => "Testing",
    //         'user_id' => 4, // dosen 2
    //         'semester' => 2,
    //         'major_id' => 1 // teknik informatika
    //     ]);
    //     $response2->assertRedirect(route('admin.achievements.index'));
    // }

    public function test_delete()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $achievement = Achievement::create([
            'user_id' => 3,
            'kpi_period_id' => 1,
            'title' => 'test',
            'position' => 1,
        ]);

        $response2 = $this->actingAs($super_admin)->delete(route('admin.achievements.destroy', $achievement->id));
        $response2->assertRedirect(route('admin.achievements.index'));
    }
}
