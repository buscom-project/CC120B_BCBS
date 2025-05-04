<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Inertia\Inertia;
use App\Models\Booking;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with([
            'user',
            'busSchedule.schedule',
            'busSchedule.route',
            'busSchedule.bus'
        ])->get();
    
        return Inertia::render('Payment/Index', [
            'bookings' => $bookings,
        ]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string',
            'payment_method' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        $payment = Payment::create($data);

        return response()->json($payment, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
