<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFeedback extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function kpi()
    {
        return $this->belongsTo(KpiPeriod::class);
    }

    public function feedback_question()
    {
        return $this->belongsTo(FeedbackQuestion::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
