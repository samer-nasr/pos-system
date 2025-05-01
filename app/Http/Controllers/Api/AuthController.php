<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login method
    public function login(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Use Auth::attempt() to check if credentials are correct
        if (Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password']
        ])) {
            // If successful, return a success response with the user data
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token
            ]);
        }

        // If login fails, return an error
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
