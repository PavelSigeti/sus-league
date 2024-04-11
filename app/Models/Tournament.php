<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'yacht', 'description',
    ];

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }
}
