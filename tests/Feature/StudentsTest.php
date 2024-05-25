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

    public function test_mahasiswa_akses_list_feedback_form()
    {
        // $this->seed();
        $mahasiswa = User::where('email', 'mahasiswa@mahasiswa.com')->first();

        $response = $this->actingAs($mahasiswa)->get(route('student.courses.feedback', 2));

        $response->assertStatus(200);
    }

    public function test_mahasiswa_akses_profile()
    {
        // $this->seed();
        $mahasiswa = User::where('email', 'mahasiswa@mahasiswa.com')->first();

        $response = $this->actingAs($mahasiswa)->get(route('student.profile'));

        $response->assertStatus(200);
    }

    public function test_mahasiswa_mengisi_feedback()
    {
        // $this->seed();
        $mahasiswa = User::where('email', 'mahasiswa@mahasiswa.com')->first();

        $response2 = $this->actingAs($mahasiswa)->post(route('student.store', 2), [
            "messages" => [
                "Nobis voluptatem et",
                "Ut amet quidem quis",
                "Explicabo Distincti"
            ],
            "points" => [
                "2",
                "2",
                "1"
            ],
            "question_ids" => [
                "3",
                "1",
                "2"
            ],
            "questions" => [
                "Lorem  consectetur adipisicing elit.",
                "lorem ipsum dolor",
                "Lorem ipsum dolor sit amet consectetur adipisicing elit."
            ]
        ]);
        $response2->assertSessionHas('success');
    }
}
