<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
    {
        protected $fillable = [
            'shift_id',
            'user_id',
            'check_in_time',
            'check_out_time',
            'check_in_lat',
            'check_in_lng',
            'check_out_lat',
            'check_out_lng',
            'status'
        ];

        public function shift()
        {
            return $this->belongsTo(Shift::class);
        }

        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }
