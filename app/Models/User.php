<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'phone_number', 'date_of_birth', 'origin', 'country', 'role', 'plan', 'daily_quota', 'last_quota_reset', 'premium_expires_at', 'github_id', 'github_token', 'google_id', 'google_token'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected static function booted()
    {
        // When loading from DB — auto-fix free users with illegal quota
        static::retrieved(function ($user) {
            if ($user->plan !== 'premium' && $user->daily_quota > 20) {
                $user->daily_quota = 20;
                $user->saveQuietly();
            }
        });

        // Before saving — force cap for free users
        static::saving(function ($user) {
            if ($user->plan !== 'premium' && $user->daily_quota > 20) {
                $user->daily_quota = 20;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'premium_expires_at' => 'datetime',
            'last_quota_reset' => 'datetime',
        ];
    }

    public function codeDrafts()
    {
        return $this->hasMany(CodeDraft::class);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if user has active premium plan.
     */
    public function isPremium(): bool
    {
        if ($this->plan !== 'premium') {
            return false;
        }

        // If has an expiry date, check it
        if ($this->premium_expires_at) {
            if (now()->lessThan($this->premium_expires_at)) {
                return true;
            }

            // Premium expired — auto-reset immediately
            $this->plan = 'free';
            $this->daily_quota = 20;
            $this->premium_expires_at = null;
            $this->save();

            return false;
        }

        // No expiry = premium forever (legacy / manual grants)
        return true;
    }

    /**
     * Get remaining daily quota.
     */
    public function getRemainingQuota(): int
    {
        return max(0, $this->daily_quota);
    }

    /**
     * Reset daily quota if a new day has started.
     * Also enforce max quota for free users (anti-DB-tampering).
     */
    public function resetDailyQuotaIfNeeded(): void
    {
        $now = now();
        $lastReset = $this->last_quota_reset;

        // Reset if never reset, or if last reset was on a different day
        if (!$lastReset || !$lastReset->isSameDay($now)) {
            $this->daily_quota = 20;
            $this->last_quota_reset = $now;
            $this->save();
        }

        // Force cap for free users — even if someone manually changes DB
        if ($this->plan !== 'premium' && $this->daily_quota > 20) {
            $this->daily_quota = 20;
            $this->save();
        }
    }
}
