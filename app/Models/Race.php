<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id', 'group_id', 'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();;
    }

    public function stages()
    {
        return $this->belongsTo(Stage::class);
    }
}
