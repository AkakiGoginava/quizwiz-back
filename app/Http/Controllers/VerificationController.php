<?php

namespace App\Http\Controllers;

use App\Models\EmailVerificationToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    public function verifyEmail(): JsonResponse
    {
        $token = request('token');

        $record = EmailVerificationToken::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $record) {
            return response()->json(['message' => 'Your email verification token has expired or you are already verified.'], 422);
        }

        $user = User::find($record->user_id);

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 409);
        }

        $user->markEmailAsVerified();
        $record->delete();

        return response()->json(['message' => 'Email verified'], 200);
    }

    public function checkVerifyToken(): JsonResponse
    {
        $token = request('token');

        $record = EmailVerificationToken::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $record) {
            return response()->json(['message' => 'Your email verification token has expired, please try again.'], 422);
        }

        $user = User::find($record->user_id);

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        return response()->json(['message' => 'Verification link is valid.'], 200);
    }
}
