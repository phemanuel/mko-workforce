<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftAssignment extends Model
{
    protected $fillable = [
        'shift_id',
        'employee_id',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | SHIFT
    |--------------------------------------------------------------------------
    */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE
    |--------------------------------------------------------------------------
    */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}