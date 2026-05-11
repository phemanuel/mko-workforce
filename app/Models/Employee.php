<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
        protected $fillable = [
        'user_id',
        'employee_code',
        'employment_type',
        'start_date',
        'primary_role',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function profile()
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    public function workEligibility()
    {
        return $this->hasOne(EmployeeWorkEligibility::class);
    }

    public function payroll()
    {
        return $this->hasOne(EmployeePayroll::class);
    }

    public function roleDetail()
    {
        return $this->hasOne(EmployeeRoleDetail::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function isCompliant()
    {
        return $this->documents()
            ->where('status', '!=', 'approved')
            ->count() === 0;
    }

    public function shifts()
    {
        return $this->belongsToMany(
            Shift::class,
            'shift_assignments'
        );
    }
    
}
