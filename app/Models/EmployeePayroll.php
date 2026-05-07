<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePayroll extends Model
{
    protected $fillable = [
        'employee_id',
        'bank_name',
        'account_number',
        'sort_code',
        'utr',
        'payment_type',
    ];

    protected $casts = [
        'account_number' => 'encrypted',
        'sort_code' => 'encrypted',
        'utr' => 'encrypted',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}