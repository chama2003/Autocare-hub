<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 'trigger_type', 'due_date', 'due_mileage_km', 'notified', 'notified_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'notified' => 'boolean',
            'notified_at' => 'datetime',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
