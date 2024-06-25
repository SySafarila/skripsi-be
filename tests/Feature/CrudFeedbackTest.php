<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\FeedbackQuestion;
use App\Models\KpiPeriod;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserFeedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudFeedbackTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_umpan_balik_read()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $response = $this->actingAs($admin)->get(route('admin.feedbacks.index'));

        $response->assertStatus(200);
    }

    public function test_umpan_balik_delete()
    {
        // $this->seed();
        $admin = User::where('email', 'admin@admin.com')->first();

        $feedbacks = [];
        array_push($feedbacks, [
            'user_id' => 3, // dosen
            'sender_id' => 6, // mahasiswa
            'kpi_period_id' => 1, // kpi
            'feedback_question_id' => 1,
            'course_id' => 1,
            'question' => 'testing',
            'point' => 5,
            'message' => 'testing',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('user_feedback')->insert($feedbacks);

        $userFeedback = UserFeedback::where('message', 'testing')->first();
        $response2 = $this->actingAs($admin)->delete(route('admin.feedbacks.destroy', $userFeedback->id));
        $response2->assertRedirect(route('admin.feedbacks.index'));
    }
}
