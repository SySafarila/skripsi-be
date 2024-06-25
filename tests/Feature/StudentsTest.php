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

class StudentsTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_mahasiswa_akses_homepage()
    {
        // $this->seed();
        $mahasiswa = User::where('email', 'mahasiswa@mahasiswa.com')->first();

        $response = $this->actingAs($mahasiswa)->get(route('student.index'));

        $response->assertStatus(200);
    }

    public function test_mahasiswa_akses_list_feedback()
    {
        // $this->seed();
        $mahasiswa = User::where('email', 'mahasiswa@mahasiswa.com')->first();

        $response = $this->actingAs($mahasiswa)->get(route('student.courses.index'));

        $response->assertStatus(200);
    }

    public function test_mahasiswa_akses_list_feedback_form_dosen()
    {
        // $this->seed();
        $mahasiswa = User::where('email', 'mahasiswa@mahasiswa.com')->first();

        $response = $this->actingAs($mahasiswa)->get(route('student.courses.feedback', 2));

        $response->assertStatus(200);
    }

    public function test_mahasiswa_akses_list_feedback_form_tendik()
    {
        // $this->seed();
        $mahasiswa = User::where('email', 'mahasiswa@mahasiswa.com')->first();

        $response = $this->actingAs($mahasiswa)->get(route('student.courses.feedback.nonedu', 3));

        $response->assertStatus(200);
    }

    public function test_mahasiswa_akses_profile()
    {
        // $this->seed();
        $mahasiswa = User::where('email', 'mahasiswa@mahasiswa.com')->first();

        $response = $this->actingAs($mahasiswa)->get(route('student.profile'));

        $response->assertStatus(200);
    }

    public function test_mahasiswa_mengisi_feedback_dosen()
    {
        // $this->seed();
        $mahasiswa = User::where('email', 'mahasiswa@mahasiswa.com')->first();

        $response = $this->actingAs($mahasiswa)->get(route('student.courses.feedback', 2));
        $response->assertStatus(200);

        $response2 = $this->actingAs($mahasiswa)->post(route('student.store', 2), [
            "messages" => [
                "Id cillum exercitat",
                "In excepteur sint et",
                "Animi atque error i"
            ],
            "points" => [
                "5",
                "5",
                "4"
            ],
            "question_ids" => [
                "3",
                "1",
                "2"
            ],
            "questions" => [
                "Apakah A dan B sama dengan AB?",
                "Apakah A sama dengan B?",
                "Apakah B sama dengan A?"
            ]
        ]);
        $response2->assertSessionHas('success');

        $response3 = $this->actingAs($mahasiswa)->get(route('student.courses.feedback', 2));
        $response3->assertStatus(200);
    }

    public function test_mahasiswa_mengisi_feedback_tendik()
    {
        // $this->seed();
        $mahasiswa = User::where('email', 'mahasiswa@mahasiswa.com')->first();

        $response = $this->actingAs($mahasiswa)->get(route('student.courses.feedback.nonedu', 3));
        $response->assertStatus(200);

        $response2 = $this->actingAs($mahasiswa)->post(route('student.store.nonedu', 3), [
            "messages" => [
                "Officiis dolorum eve",
                "Facilis in repellend"
            ],
            "points" => [
                "5",
                "2"
            ],
            "question_ids" => [
                "4",
                "5"
            ],
            "questions" => [
                "Apakah C dan B sama dengan CB?",
                "Apakah X dan A sama dengan XA?"
            ]
        ]);
        $response2->assertSessionHas('success');

        $response3 = $this->actingAs($mahasiswa)->get(route('student.courses.feedback.nonedu', 3));
        $response3->assertStatus(200);
    }

    public function test_mahasiswa_mengubah_pengaturan()
    {
        $mahasiswa = User::where('email', 'mahasiswa@mahasiswa.com')->first();

        $response = $this->actingAs($mahasiswa)->get(route('settings.index'));
        $response->assertStatus(200);

        $response2 = $this->actingAs($mahasiswa)->patch(route('settings.update', [
            'email' => 'mahasiswa@mahasiswa.com',
            'password' => 'password2',
            'update_image' => false
        ]));
        $response2->assertStatus(200);
    }
}
