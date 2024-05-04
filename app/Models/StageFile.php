<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id', 'path', 'title',
    ];

}
