<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tendik() {
        return $this->belongsTo(TendikPosition::class, 'tendik_position_id', 'id');
    }
}
