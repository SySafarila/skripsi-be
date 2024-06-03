<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\KpiPeriod;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudCourseTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_mata_kuliah_create()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response1 = $this->actingAs($super_admin)->get(route('admin.courses.create'));
        $response1->assertStatus(200);

        $response = $this->actingAs($super_admin)->post(route('admin.courses.store'), [
            'name' => "Testing",
            'user_id' => 3, // dosen
            'semester' => 2,
            'major_id' => 1 // teknik informatika
        ]);

        $response->assertRedirect(route('admin.courses.index'));
    }

    public function test_mata_kuliah_read()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->get(route('admin.courses.index'));

        $response->assertStatus(200);
    }

    public function test_mata_kuliah_update()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.courses.store'), [
            'name' => "Testing",
            'user_id' => 3, // dosen
            'semester' => 2,
            'major_id' => 1 // teknik informatika
        ]);
        $response->assertRedirect(route('admin.courses.index'));

        $course = Course::where('name', 'Testing')->first();

        $response1 = $this->actingAs($super_admin)->get(route('admin.courses.edit', $course->id));
        $response1->assertStatus(200);

        $response2 = $this->actingAs($super_admin)->patch(route('admin.courses.update', $course->id), [
            'name' => "Testing",
            'user_id' => 4, // dosen 2
            'semester' => 2,
            'major_id' => 1 // teknik informatika
        ]);
        $response2->assertRedirect(route('admin.courses.index'));
    }

    public function test_mata_kuliah_delete()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.courses.store'), [
            'name' => "Testing",
            'user_id' => 3, // dosen
            'semester' => 2,
            'major_id' => 1 // teknik informatika
        ]);
        $response->assertRedirect(route('admin.courses.index'));

        $kpi = Course::where('name', 'Testing')->first();
        $response2 = $this->actingAs($super_admin)->delete(route('admin.courses.destroy', $kpi->id));
        $response2->assertRedirect(route('admin.courses.index'));
    }
}
