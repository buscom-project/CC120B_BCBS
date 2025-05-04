<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    protected $fillable = ['start_location', 'end_location'];

    public function busSchedules(): HasMany
    {
        return $this->hasMany(BusSchedule::class);
    }
}
