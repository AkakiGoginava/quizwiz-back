<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
        auth('web')->logout();

        $this->setRememberToken(null);
        $this->save();

        $this->notify(new VerifyEmailNotification);
    }

    public function quizzes(): BelongsToMany {
        return $this->belongsToMany(Quiz::class);
    }
}
