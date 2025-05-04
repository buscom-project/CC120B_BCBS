<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus;
use Inertia\Inertia;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buses = Bus::latest()->get();

        return Inertia::render('Bus/Index', [
            'buses' => $buses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'bus_name' => 'required|string',
            'total_seats' => 'required|integer|min:1',
        ]);

        $bus = Bus::create($data);

        return response()->json($bus, 201);
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
    public function destroy($id)
    {
        $bus = Bus::findOrFail($id);
        $bus->delete();

        return response()->json(['message' => 'Bus deleted successfully.']);
    }
}
