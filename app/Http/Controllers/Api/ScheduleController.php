<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = Schedule::all();
        return Inertia::render('Schedule/Index', [
            'schedules' => $schedules,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'departure_time' => 'required|date',
            'arrival_time' => 'nullable|date|after_or_equal:departure_time',
            'availability' => 'required|string',
        ]);

        $schedule = Schedule::create($data);

        return response()->json($schedule, 201);
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
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return response()->json(['message' => 'Schedule deleted successfully.']);
    }
}
