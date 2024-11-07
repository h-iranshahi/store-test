<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHandler;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;


class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->input('role', User::ROLE_USER),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ResponseHandler::success('User registered successfully', [
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return ResponseHandler::error('Invalid credentials', null, 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ResponseHandler::success('User logged in successfully', [
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function me(): JsonResponse
    {
        $user = auth()->user();
        return ResponseHandler::success('Authenticated user info', $user->toArray());
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return ResponseHandler::success('Logged out successfully', null, 204);
    }
}
