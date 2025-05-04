<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BusSchedule;
use App\Models\Bus;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $schedules = BusSchedule::with(['bus', 'route', 'schedule'])
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'price' => $schedule->price,
                    'bus' => $schedule->bus ? [
                        'id' => $schedule->bus->id,
                        'bus_name' => $schedule->bus->bus_name, 
                        'total_seats' => $schedule->bus->total_seats
                    ] : null,
                    'route' => $schedule->route ? [
                        'id' => $schedule->route->id,
                        'start_location' => $schedule->route->start_location,
                        'end_location' => $schedule->route->end_location 
                    ] : null,
                    'schedule' => $schedule->schedule ? [
                        'id' => $schedule->schedule->id,
                        'departure_time' => $schedule->schedule->departure_time,
                        'arrival_time' => $schedule->schedule->arrival_time
                    ] : null
                ];
            });

        $bookings = Booking::where('user_id', Auth::id())
            ->with(['busSchedule.bus', 'busSchedule.route', 'busSchedule.schedule'])
            ->latest()
            ->get();
        
        return Inertia::render('Booking/Index', [
            'schedules' => $schedules,
            'bookings' => $bookings,
            'authUserId' => Auth::id(),
        ]);
    }

    

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'bus_schedule_id' => 'required|exists:bus_schedules,id',
            'seat_numbers' => 'required|array',
            'seat_numbers.*' => 'string',
            'total_price' => 'required|numeric',
        ]);
        
        $schedule = BusSchedule::with('bus')->findOrFail($data['bus_schedule_id']);
    
        $requestSeats = array_map('strval', $data['seat_numbers']);
    
        $existingSeats = Booking::where('bus_schedule_id', $data['bus_schedule_id'])
            ->whereIn('seat_number', $requestSeats)
            ->pluck('seat_number')
            ->toArray();
    
        if (!empty($existingSeats)) {
            return response()->json([
                'message' => 'Some seats are already booked',
                'conflicts' => $existingSeats,
                'errors' => [
                    'seats' => 'Some seats are unavailable'
                ]
            ], 422);
        }
    
        $bookings = [];
        foreach ($requestSeats as $seat) {
            $bookings[] = Booking::create([
                'user_id' => $data['user_id'],
                'bus_schedule_id' => $data['bus_schedule_id'],
                'seat_number' => $seat,
                'total_price' => $data['total_price'] / count($requestSeats),
                'payment_status' => 'pending'
            ]);
        }
    
        return response()->json([
            'message' => 'Booking successful',
            'data' => $bookings
        ], 201);
    }
    


    public function getAvailableSeats($scheduleId)
    {
        $schedule = BusSchedule::with(['bus', 'bookings'])->findOrFail($scheduleId);
        $allSeats = range(1, $schedule->bus->total_seats);
        $bookedSeats = $schedule->bookings->pluck('seat_number')->toArray();
        
        $availableSeats = array_diff($allSeats, $bookedSeats);
        
        return response()->json([
            'bus_capacity' => $schedule->bus->total_seats,
            'available_seats' => array_values($availableSeats),
            'booked_seats' => $bookedSeats
        ]);
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'payment_status' => 'required|in:pending,paid,cancelled,failed'
        ]);
        
        $booking->update(['payment_status' => $request->payment_status]);
        
        return response()->json(['message' => 'Payment status updated successfully']);
    }
}