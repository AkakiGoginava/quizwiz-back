<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
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

        try {
            $user = User::create([
                'name'     => $attributes['name'],
                'email'    => $attributes['email'],
                'password' => $attributes['password'],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Could not register user, make sure to use unique name and email',
                'error'   => $e->getMessage(),
            ], 422);
        }

        Auth::login($user);

        event(new Registered($user));

        return response()->json([
            'message' => 'You successfully created profile on quizwiz platform. enjoy!',
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
                'message' => 'You have logged in successfully. Welcome back!',
            ], 201);
        }

        return response()->json([
            'message' => 'Credentials you provided do not match our records',
            'errors'  => [
                'email'    => ['Invalid credentials.'],
                'password' => ['Invalid credentials.'],
            ],
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
            'message' => 'Logged out successfully, see you later!',
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

    public function checkPasswordToken(): JsonResponse
    {
        $token = request('token');
        $email = request('email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $broker = Password::broker();
        if (! $broker->tokenExists($user, $token)) {
            return response()->json(['message' => 'Your password reset token has expired, please try again.'], 422);
        }

        return response()->json(['message' => 'Token is valid.'], 200);
    }
}
