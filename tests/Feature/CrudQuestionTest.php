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
        $admin = User::where('email', 'admin@admin.com')->first();

        $response1 = $this->actingAs($admin)->get(route('admin.questions.create'));
        $response1->assertStatus(200);

        $response = $this->actingAs($admin)->post(route('admin.questions.store'), [
            'question' => "Testing",
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);

        $response->assertRedirect(route('admin.questions.index'));
    }

    public function test_kuesioner_read()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->get(route('admin.questions.index'));

        $response->assertStatus(200);
    }

    public function test_kuesioner_update()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.questions.store'), [
            'question' => "Testing",
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        $response->assertRedirect(route('admin.questions.index'));

        $feedbackQuestion = FeedbackQuestion::where('question', 'Testing')->first();
        $response1 = $this->actingAs($admin)->get(route('admin.questions.edit', $feedbackQuestion->id));
        $response1->assertStatus(200);
        $response2 = $this->actingAs($admin)->patch(route('admin.questions.update', $feedbackQuestion->id), [
            'question' => "Testing 2",
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        $response2->assertRedirect(route('admin.questions.index'));
    }

    public function test_kuesioner_delete()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->post(route('admin.questions.store'), [
            'question' => "Testing",
            'tendik_position_id' => 1 // mahasiswa to dosen
        ]);
        $response->assertRedirect(route('admin.questions.index'));

        $feedbackQuestion = FeedbackQuestion::where('question', 'Testing')->first();
        $response2 = $this->actingAs($admin)->delete(route('admin.questions.destroy', $feedbackQuestion->id));
        $response2->assertRedirect(route('admin.questions.index'));
    }
}
