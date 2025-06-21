<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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
        $user = $request->user();

        auth('web')->logout();

        $user->setRememberToken(null);
        $user->save();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            $user->setRememberToken(null);
            $user->save();
        }

        return response()->json([
            'message' => 'Logged out successfully',
        ], 201);
    }

    public function getUser(Request $request): JsonResponse
    {
        $user = $request->user()->load('quizzes');

        if (! $user) {
            return response()->json([
                'error' => 'Unauthenticated',
            ], 401);
        }

        return response()->json(new UserResource($user));
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $status = Password::sendResetLink($attributes);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)], 200);
        }

        return response()->json(['message' => __($status)], 422);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $status = Password::reset(
            $attributes,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 200);
        }

        return response()->json(['message' => __($status)], 422);
    }
}
