<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'bus_schedule_id', 'seat_number', 'payment_status', 'total_price'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function busSchedule(): BelongsTo
    {
        return $this->belongsTo(BusSchedule::class);
    }
}
