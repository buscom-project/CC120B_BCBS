<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = ['bus_name', 'total_seats'];

    public function busSchedules(): HasMany
    {
        return $this->hasMany(BusSchedule::class);
    }
}
