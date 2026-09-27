<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['category', 'title', 'amount', 'expense_date', 'recorded_by', 'notes'];

    protected function casts(): array
    {
        return ['expense_date' => 'date'];
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
