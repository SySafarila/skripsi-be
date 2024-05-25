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

class CrudQuestionTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_kuesioner_create()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.questions.store'), [
            'question' => "Testing",
            'type' => "mahasiswa-to-dosen"
        ]);

        $response->assertRedirect(route('admin.questions.index'));
    }

    public function test_kuesioner_read()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->get(route('admin.questions.index'));

        $response->assertStatus(200);
    }

    public function test_kuesioner_update()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.questions.store'), [
            'question' => "Testing",
            'type' => "mahasiswa-to-dosen"
        ]);
        $response->assertRedirect(route('admin.questions.index'));

        $kpi = FeedbackQuestion::where('question', 'Testing')->first();
        $response2 = $this->actingAs($super_admin)->patch(route('admin.questions.update', $kpi->id), [
            'question' => "Testing 2",
            'type' => "mahasiswa-to-dosen"
        ]);
        $response2->assertRedirect(route('admin.questions.index'));
    }

    public function test_kuesioner_delete()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.questions.store'), [
            'question' => "Testing",
            'type' => "mahasiswa-to-dosen"
        ]);
        $response->assertRedirect(route('admin.questions.index'));

        $kpi = FeedbackQuestion::where('question', 'Testing')->first();
        $response2 = $this->actingAs($super_admin)->delete(route('admin.questions.destroy', $kpi->id));
        $response2->assertRedirect(route('admin.questions.index'));
    }
}
