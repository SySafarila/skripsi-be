<?php

use App\Models\FeedbackQuestion;
use App\Models\KpiPeriod;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(User::class, 'sender_id')->constrained('users', 'id');
            $table->foreignIdFor(KpiPeriod::class)->constrained();
            // $table->foreignIdFor(FeedbackQuestion::class)->constrained();
            $table->text('question');
            $table->integer('point');
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_feedback');
    }
};
