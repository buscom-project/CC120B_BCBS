<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusSchedule;
use Inertia\Inertia;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route;

class BusScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $buses = Bus::all();
        $routes = Route::all();
        $schedules = Schedule::all();
        $busSchedules = BusSchedule::with(['bus', 'route', 'schedule'])->get();

        return Inertia::render('Schedule/Bus_Schedule/Index', [
            'buses' => $buses,
            'routes' => $routes,
            'schedules' => $schedules,
            'busSchedules' => $busSchedules,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'schedule_id' => 'required|exists:schedules,id',
            'price' => 'required|numeric|min:0',
        ]);

        $busSchedule = BusSchedule::create($data);

        return response()->json($busSchedule, 201);
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
        $busSchedule = BusSchedule::find($id);

        if ($busSchedule) {
            $busSchedule->delete();
            return response()->json(['message' => 'Bus schedule deleted successfully.']);
        }

        return response()->json(['message' => 'Bus schedule not found.'], 404);
    }

}
