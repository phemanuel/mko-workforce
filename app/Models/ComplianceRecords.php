<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceRecords extends Model
{
    //
    protected $fillable = [
        'employee_id',
        'type',
        'reference_number',
        'issue_date',
        'expiry_date',
        'alert_2_months',
        'alert_3_months',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
