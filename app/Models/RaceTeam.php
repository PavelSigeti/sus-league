<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaceTeam extends Model
{
    use HasFactory;

    protected $table = 'race_team';

    protected $fillable = ['race_id', 'team_id', 'place'];
}
