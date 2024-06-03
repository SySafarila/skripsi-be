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
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response1 = $this->actingAs($super_admin)->get(route('admin.majors.create'));
        $response1->assertStatus(200);

        $response = $this->actingAs($super_admin)->post(route('admin.majors.store'), [
            "major" => "Incididunt enim qui",
        ]);

        $response->assertRedirect(route('admin.majors.index'));
    }

    public function test_jurusan_read()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->get(route('admin.majors.index'));

        $response->assertStatus(200);
    }

    public function test_jurusan_update()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.majors.store'), [
            "major" => "Incididunt enim qui"
        ]);
        $response->assertRedirect(route('admin.majors.index'));

        $major = Major::first();
        $response1 = $this->actingAs($super_admin)->get(route('admin.majors.edit', $major->id));
        $response1->assertStatus(200);
        $response2 = $this->actingAs($super_admin)->patch(route('admin.majors.update', $major->id), [
            "major" => "Testing"
        ]);
        $response2->assertRedirect(route('admin.majors.index'));
    }

    public function test_jurusan_delete()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.majors.store'), [
            "major" => "Incididunt enim qui",
        ]);
        $response->assertRedirect(route('admin.majors.index'));

        $major = Major::first();
        $response2 = $this->actingAs($super_admin)->delete(route('admin.majors.destroy', $major->id));
        $response2->assertRedirect(route('admin.majors.index'));
    }
}
