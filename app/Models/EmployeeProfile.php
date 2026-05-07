<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    protected $fillable = [
        'employee_id',
        'right_to_work_status',
        'share_code',
        'right_to_work_expiry',
        'employee_code',
        'employment_type',
        'start_date',
        'availability',
        'primary_role',
        'secondary_skills',
        'role_data',
    ];

    /**
     * CAST JSON FIELDS PROPERLY
     */
    protected $casts = [
        'availability' => 'array',
        'secondary_skills' => 'array',
        'role_data' => 'array',
        'right_to_work_expiry' => 'date',
        'start_date' => 'date',
    ];

    /**
     * PROFILE BELONGS TO EMPLOYEE
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}