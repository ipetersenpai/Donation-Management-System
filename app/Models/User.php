<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Mail\CustomResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'suffix', 'birth_date',
        'contact_no', 'home_address', 'gender', 'email', 'role', 'verified_status',
        'email_verified_at', 'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Override the default password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->email)->send(new CustomResetPassword($token, $this->email));
    }

    public function updateSessionTimeout()
    {
        $configSessionLifetime = Config::get('session.lifetime');
        $newSessionLifetime = 30; // 30 minutes

        if ($newSessionLifetime > $configSessionLifetime) {
            Config::set('session.lifetime', $newSessionLifetime);
        }
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
