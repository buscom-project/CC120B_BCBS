<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\User;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Booking;
use App\Models\BusSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect if user is not admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('booking.index');
        }        

        // Stats
        $customersCount = User::count();
        $busesCount = Bus::count();
        $routesCount = Route::count();
        $bookingsCount = Booking::count();
        $todayBookingsCount = Booking::whereDate('created_at', Carbon::today())->count();

        // Recent Bookings with relations
        $recentBookings = Booking::with('busSchedule.bus', 'busSchedule.route', 'busSchedule.schedule')
        ->latest()
        ->take(5)
        ->get()
        ->map(function ($booking) {
            return [
                'id' => $booking->id,
                'seat_number' => $booking->seat_number,
                'total_price' => $booking->total_price,
                'created_at' => $booking->created_at->toDateTimeString(),
                'bus' => [
                    'bus_name' => $booking->busSchedule->bus->bus_name ?? 'N/A',
                ],
                'route' => [
                    'start_location' => $booking->busSchedule->route->start_location ?? 'N/A',
                    'end_location' => $booking->busSchedule->route->end_location ?? 'N/A',
                ],
                'schedule' => [
                    'departure_time' => optional($booking->busSchedule->schedule)->departure_time,
                    'arrival_time' => optional($booking->busSchedule->schedule)->arrival_time,
                ],
            ];
        });



        // Bus Availability: compute how many schedules still have available seats
        $busAvailability = Bus::with(['busSchedules' => function ($query) {
            $query->withCount(['bookings']);
        }])
        ->get()
        ->map(function ($bus) {
            $schedule = $bus->busSchedules->first(); // just show 1 schedule per bus for summary
            $bookedSeats = $schedule?->bookings_count ?? 0;
            $availableSeats = $bus->total_seats - $bookedSeats;

            return [
                'bus_name' => $bus->bus_name,
                'available_seats' => $availableSeats,
                'total_seats' => $bus->total_seats,
                'percentage' => round(($availableSeats / $bus->total_seats) * 100),
            ];
        })
        ->take(5);

        // Final data
        return Inertia::render('Dashboard', [
            'stats' => [
                ['title' => 'Customers', 'value' => $customersCount, 'change' => '+12%', 'trend' => 'up', 'icon' => 'Users'],
                ['title' => 'Buses', 'value' => $busesCount, 'change' => '+3', 'trend' => 'up', 'icon' => 'Bus'],
                ['title' => 'Routes', 'value' => $routesCount, 'change' => '2 new', 'trend' => 'up', 'icon' => 'MapPin'],
                ['title' => 'Today', 'value' => now()->format('M d'), 'change' => "{$todayBookingsCount} bookings", 'trend' => 'neutral', 'icon' => 'CalendarDays'],
                ['title' => 'Bookings', 'value' => $bookingsCount, 'change' => '+24', 'trend' => 'up', 'icon' => 'Ticket'],
            ],
            'recentBookings' => $recentBookings,
            'busAvailability' => $busAvailability,
        ]);
    }
}
