<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
    {
        protected $fillable = [

            'shift_assignment_id',

            'shift_id',

            'employee_id',

            'check_in_time',
            'check_out_time',

            'check_in_lat',
            'check_in_lng',

            'check_out_lat',
            'check_out_lng',

            'late_minutes',
            'early_leave_minutes',

            'status',

            'remarks',

            'worked_hours',
            'worked_minutes',
        ];

        protected $casts = [
            'check_in_time'=>'datetime',
            'check_out_time'=>'datetime'
        ];
        
        public function shift()
        {
            return $this->belongsTo(Shift::class);
        }

        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function employee()
        {
            return $this->belongsTo(Employee::class);
        }        

        public function assignment()
        {
            return $this->belongsTo(ShiftAssignment::class,'shift_assignment_id');
        }
    }
