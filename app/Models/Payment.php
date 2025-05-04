<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'status', 'payment_method', 'paid_at'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
