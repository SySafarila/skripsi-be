<?php

namespace Database\Seeders;

use App\Models\FeedbackQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedbackQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FeedbackQuestion::create([
            'question' => 'lorem ipsum dolor',
            'type' => 'mahasiswa-to-dosen'
        ]);
    }
}
