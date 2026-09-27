<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'base_labor_cost', 'estimated_minutes'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
