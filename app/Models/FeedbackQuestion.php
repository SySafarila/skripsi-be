<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function responses() {
        return $this->hasMany(UserFeedback::class);
    }
}
