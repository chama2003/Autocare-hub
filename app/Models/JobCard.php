<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_card_number', 'vehicle_id', 'appointment_id', 'mechanic_id', 'created_by',
        'mileage_at_service_km', 'customer_complaint', 'diagnosis', 'status',
        'labor_hours', 'labor_rate_per_hour', 'opened_at', 'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public const STATUSES = ['pending', 'in_progress', 'quality_check', 'completed'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function serviceItems()
    {
        return $this->hasMany(JobCardServiceItem::class);
    }

    public function partsUsed()
    {
        return $this->hasMany(JobCardPart::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function laborTotal(): float
    {
        return (float) $this->serviceItems->sum('cost') + ($this->labor_hours * $this->labor_rate_per_hour);
    }

    public function partsTotal(): float
    {
        return (float) $this->partsUsed->sum(fn ($p) => $p->quantity * $p->unit_price);
    }

    public function grandTotal(): float
    {
        return $this->laborTotal() + $this->partsTotal();
    }
}
