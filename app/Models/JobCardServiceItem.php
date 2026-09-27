<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCardServiceItem extends Model
{
    use HasFactory;

    protected $fillable = ['job_card_id', 'service_type_id', 'cost', 'notes'];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }
}
