<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function major() {
        return $this->belongsTo(Major::class);
    }

    public function feedbacks() {
        return $this->hasMany(UserFeedback::class);
    }
}
