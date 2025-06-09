<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $user = User::create([
            'name'     => $attributes['name'],
            'email'    => $attributes['email'],
            'password' => $attributes['password'],
        ]);

        Auth::login($user);

        event(new Registered($user));

        return response()->json([
            'message' => 'Registration successful',
            'user'    => $user,
        ], 200);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $credentials = Arr::only($attributes, ['email', 'password']);
        $remember = $attributes['remember'] ?? false;

        if (Auth::attempt($credentials, $remember)) {
            Cookie::queue('remember_web_' . sha1('users'), Auth::user()->getRememberToken(), 43200);

            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login successful',
            ], 201);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function getUser(Request $request): User
    {
        return $request->user();
    }
}
