<?php

namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;


use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }
    
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
            'status' => $validatedData['status'],
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function uploadUsers(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);

            Excel::import(new UsersImport, $request->file('file'));

            return response()->json(['message' => 'Users uploaded successfully!'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors (e.g., duplicate entry)
            \Log::error('Database error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Duplicate entry found! Some records already exist.',
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Handle validation errors inside Excel import
            $failures = $e->failures();
            return response()->json([
                'message' => 'Some rows failed validation.',
                'errors' => $failures
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error uploading users: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to upload file. Please check the format and data integrity.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Validate input
            $validated = $request->validate([
                'status' => 'required|string|in:active,inactive',
            ]);

            // Update user status
            $user->update($validated);

            return response()->json(['message' => 'User updated successfully!', 'user' => $user], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, User $user)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'status' => 'required|string|in:active,inactive',
            ]);

            // Log the request data
            \Log::info('Updating user status:', [
                'user_id' => $user->id,
                'new_status' => $validated['status'],
            ]);

            // Update user status
            $user->update($validated);

            return response()->json(['message' => 'User status updated successfully!', 'user' => $user], 200);
        } catch (ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to update user status:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update user status', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
