<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'plate_number', 'make', 'model', 'type', 'manufacture_year',
        'color', 'current_mileage_km', 'service_interval_km', 'service_interval_months',
        'last_service_date', 'last_service_mileage_km',
    ];

    protected function casts(): array
    {
        return [
            'last_service_date' => 'date',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function jobCards()
    {
        return $this->hasMany(JobCard::class);
    }

    public function serviceReminders()
    {
        return $this->hasMany(ServiceReminder::class);
    }

    /**
     * Full historical service record: every completed job card for this vehicle,
     * newest first, with its services/parts/invoice eager loaded.
     */
    public function serviceHistory()
    {
        return $this->jobCards()
            ->with(['serviceItems.serviceType', 'partsUsed.sparePart', 'invoice'])
            ->orderByDesc('opened_at');
    }

    /**
     * Is this vehicle due (or nearly due) for its next recommended service?
     * Based on elapsed mileage or elapsed months since last service.
     */
    public function isServiceDue(): bool
    {
        $mileageDue = $this->last_service_mileage_km !== null
            && ($this->current_mileage_km - $this->last_service_mileage_km) >= $this->service_interval_km;

        $timeDue = $this->last_service_date !== null
            && $this->last_service_date->diffInMonths(Carbon::now()) >= $this->service_interval_months;

        // If it has never been serviced, treat as not due yet (no baseline).
        return $mileageDue || $timeDue;
    }

    public function kmUntilNextService(): ?int
    {
        if ($this->last_service_mileage_km === null) {
            return null;
        }

        $nextDueAt = $this->last_service_mileage_km + $this->service_interval_km;

        return max(0, $nextDueAt - $this->current_mileage_km);
    }
}
