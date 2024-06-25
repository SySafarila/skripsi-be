<?php

namespace Tests\Feature;

use App\Models\KpiPeriod;
use App\Models\Major;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudMajorTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_jurusan_create()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response1 = $this->actingAs($admin)->get(route('admin.majors.create'));
        $response1->assertStatus(200);

        $response = $this->actingAs($admin)->post(route('admin.majors.store'), [
            "major" => "Incididunt enim qui",
        ]);

        $response->assertRedirect(route('admin.majors.index'));
    }

    public function test_jurusan_read()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->get(route('admin.majors.index'));

        $response->assertStatus(200);
    }

    public function test_jurusan_update()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.majors.store'), [
            "major" => "Incididunt enim qui"
        ]);
        $response->assertRedirect(route('admin.majors.index'));

        $major = Major::first();
        $response1 = $this->actingAs($admin)->get(route('admin.majors.edit', $major->id));
        $response1->assertStatus(200);
        $response2 = $this->actingAs($admin)->patch(route('admin.majors.update', $major->id), [
            "major" => "Testing"
        ]);
        $response2->assertRedirect(route('admin.majors.index'));
    }

    public function test_jurusan_delete()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.majors.store'), [
            "major" => "Incididunt enim qui",
        ]);
        $response->assertRedirect(route('admin.majors.index'));

        $major = Major::first();
        $response2 = $this->actingAs($admin)->delete(route('admin.majors.destroy', $major->id));
        $response2->assertRedirect(route('admin.majors.index'));
    }
}
