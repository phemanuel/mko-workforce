<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'title',
        'description',
        'shift_date',
        'start_time',
        'end_time',
        'location',
        'role_required',
        'required_staff',
        'hourly_rate',
        'status',
        'supervisor_id',
        'instructions',
        'notes',
        'latitude',
        'longitude',
        'attachment',
    ];

    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENTS
    |--------------------------------------------------------------------------
    */
    public function assignments()
    {
        return $this->hasMany(ShiftAssignment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | EMPLOYEES
    |--------------------------------------------------------------------------
    */
    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'shift_assignments'
        );
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}