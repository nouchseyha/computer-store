<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'is_admin', 'phone', 'address', 'avatar', 'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen_at'      => 'datetime',
        'password'          => 'hashed',
        'is_admin'          => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /** Online = active within the last 5 minutes */
    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }

    /** Human-readable last seen */
    public function lastSeenLabel(): string
    {
        if (!$this->last_seen_at) return 'Never';
        if ($this->isOnline())    return 'Online';
        return $this->last_seen_at->diffForHumans();
    }
}
