<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['bus_id', 'route_id', 'schedule_id', 'price'];

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }   

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
