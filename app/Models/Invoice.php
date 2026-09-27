<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'job_card_id', 'user_id', 'labor_total', 'parts_total',
        'tax_amount', 'discount_amount', 'grand_total', 'status', 'pdf_path',
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
