<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeWorkEligibility extends Model
{
    protected $fillable = [
        'employee_id',
        'work_status',
        'share_code',
        'expiry_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}