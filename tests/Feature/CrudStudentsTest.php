<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\FeedbackQuestion;
use App\Models\KpiPeriod;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudStudentsTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_mahasiswa_create()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response1 = $this->actingAs($super_admin)->get(route('admin.students.create'));
        $response1->assertStatus(200);

        $response = $this->actingAs($super_admin)->post(route('admin.students.store'), [
            'name' => 'testing',
            'email' => null,
            'password' => 'password',
            'password_confirmation' => 'password',
            // 'role' => 'dosen',
            'identifier' => 'nim',
            'identifier_number' => 1234567890,
            'semester' => 2,
            'major_id' => 1 // teknik informatika
        ]);

        $response->assertRedirect(route('admin.students.index'));
    }

    public function test_mahasiswa_read()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->get(route('admin.students.index'));

        $response->assertStatus(200);
    }

    public function test_mahasiswa_update()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.students.store'), [
            'name' => 'testing',
            'email' => null,
            'password' => 'password',
            'password_confirmation' => 'password',
            // 'role' => 'dosen',
            'identifier' => 'nim',
            'identifier_number' => 1234567890,
            'semester' => 2,
            'major_id' => 1 // teknik informatika
        ]);
        $response->assertRedirect(route('admin.students.index'));

        $user = User::where('name', 'testing')->first();
        $response1 = $this->actingAs($super_admin)->get(route('admin.students.edit', $user->id));
        $response1->assertStatus(200);
        $response2 = $this->actingAs($super_admin)->patch(route('admin.students.update', $user->id), [
            'name' => 'testing 2',
            'email' => null,
            'password' => 'password',
            'password_confirmation' => 'password',
            // 'role' => 'dosen',
            'identifier' => 'nim',
            'identifier_number' => 1234567890,
            'semester' => 2,
            'major_id' => 1, // teknik informatika
            'is_active' => 1
        ]);
        $response2->assertRedirect(route('admin.students.index'));
    }

    public function test_mahasiswa_delete()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.students.store'), [
            'name' => 'testing',
            'email' => null,
            'password' => 'password',
            'password_confirmation' => 'password',
            // 'role' => 'dosen',
            'identifier' => 'nim',
            'identifier_number' => 1234567890,
            'semester' => 2,
            'major_id' => 1 // teknik informatika
        ]);
        $response->assertRedirect(route('admin.students.index'));

        $user = User::where('name', 'testing')->first();
        $response2 = $this->actingAs($super_admin)->delete(route('admin.students.destroy', $user->id));
        $response2->assertRedirect(route('admin.students.index'));
    }
}
