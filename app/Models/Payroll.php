<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_number',
        'period_start',
        'period_end',
        'total_shifts',
        'total_hours',
        'gross_pay',
        'allowance',
        'bonus',
        'deduction',
        'tax',
        'net_pay',
        'status',
        'payment_date',
        'payment_reference',
        'generated_by',
        'approved_by',
        'remarks',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'payment_date' => 'date',

        'total_hours' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'allowance' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deduction' => 'decimal:2',
        'tax' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function items()
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getFormattedGrossPayAttribute()
    {
        return number_format($this->gross_pay, 2);
    }

    public function getFormattedNetPayAttribute()
    {
        return number_format($this->net_pay, 2);
    }
}