<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = ['name', 'email', 'image', 'password', 'email_verified_at'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function getImageAttribute($value)
    {
        return $value
            ? asset($value)
            : asset('images/default-profile.jpg');
    }

    public function sendPasswordResetNotification($token): void
    {
        $frontendUrl = env('FRONTEND_URL');

        $url = "{$frontendUrl}/reset-password?token=" . $token . '&email=' . urlencode($this->email);

        $this->notify(new ResetPasswordNotification($url));
    }

    public function sendEmailVerificationNotification(): void
    {
        $token = Str::random(64);
        EmailVerificationToken::create([
            'user_id'    => $this->id,
            'token'      => $token,
            'expires_at' => Carbon::now()->addMinutes(120),
        ]);

        $this->notify(new VerifyEmailNotification($token));
    }

    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class)->withPivot('points', 'complete_time', 'created_at');
    }
}
