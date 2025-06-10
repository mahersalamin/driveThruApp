<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponseTrait;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'mobile'   => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'mobile'   => $validated['mobile'] ?? null,
            'password' => bcrypt($validated['password']),
        ]);

        return $this->successResponse($user, 'Registered successfully', 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Invalid credentials.', 422, ['email' => ['Invalid credentials.']]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user'  => $user,
        ], 'Login successful');
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->successResponse(null, 'Logged out successfully');
    }
}
