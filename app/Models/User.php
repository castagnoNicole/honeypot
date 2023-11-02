<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function isEnabled():bool{
        return $this->is_enabled;
    }

    public function status(): string
    {
        return $this->lastActivity() ? 'Logged in' : 'Logged out';
    }

    public function lastActivity(): string
    {
        if($this->latestSession()) {
            return Carbon::parse($this->latestSession()->last_activity)->diffForHumans();
        }
        return '';
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function latestSession()
    {
        return $this->sessions()
            ->orderBy('last_activity', 'desc')
            ->first();
    }
    public function creationDate(): string{
        return Carbon::parse($this->created_at)->format('Y-m-d');
    }
}
