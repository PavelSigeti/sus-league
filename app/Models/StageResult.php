<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id', 'user_id', 'result',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}
