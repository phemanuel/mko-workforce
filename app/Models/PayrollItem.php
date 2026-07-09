<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'attendance_id',
        'shift_id',
        'shift_title',
        'shift_date',
        'worked_minutes',
        'hours_worked',
        'hourly_rate',
        'amount',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'hours_worked' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}