<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id', 'register_start', 'register_end',
        'race_start', 'title', 'excerpt',
        'description', 'status', 'race_amount_drop',
        'race_amount_group_drop', 'race_amount_fleet_drop', 'participant_text',
    ];

    public function getRegisterStartAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }
    public function getRegisterEndAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }
    public function getRaceStartAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function races()
    {
        return $this->hasMany(Race::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
