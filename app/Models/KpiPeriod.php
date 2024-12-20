<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiPeriod extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function presences() {
        return $this->hasMany(UserPresence::class);
    }

    public function feedbacks() {
        return $this->hasMany(UserFeedback::class);
    }

    public function achievements() {
        return $this->hasMany(Achievement::class);
    }

    public function points() {
        return $this->hasMany(Point::class);
    }
}
