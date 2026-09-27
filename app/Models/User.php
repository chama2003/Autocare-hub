<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ---- Role helpers ----
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isStaff(): bool   { return $this->role === 'staff'; }
    public function isMechanic(): bool{ return $this->role === 'mechanic'; }
    public function isCustomer(): bool{ return $this->role === 'customer'; }

    // ---- Relationships ----
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function jobCardsAsMechanic()
    {
        return $this->hasMany(JobCard::class, 'mechanic_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
