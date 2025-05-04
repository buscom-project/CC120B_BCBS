<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['departure_time', 'arrival_time', 'availability'];

    public function busSchedules(): HasMany
    {
        return $this->hasMany(BusSchedule::class);
    }
}

