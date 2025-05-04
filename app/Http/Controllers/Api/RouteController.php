<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Route;
use Inertia\Inertia;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $route = Route::latest()->get();

        return Inertia::render('Route/Index', [
            'routes' => $route,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'start_location' => 'required|string',
            'end_location' => 'required|string',
        ]);

        $route = Route::create($data);

        return response()->json($route, 201);
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
        $route = Route::findOrFail($id);
        $route->delete();

        return response()->json(['message' => 'Route deleted successfully']);
    }
}
