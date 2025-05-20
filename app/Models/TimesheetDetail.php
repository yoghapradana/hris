<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimesheetDetail extends Model
{
    protected $fillable = [
        'timesheet_id',
        'time_start',
        'time_end',
        'total_time',
        'job_code',
        'job_descriptions',
        'remark',
    ];

    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class, 'timesheet_id', 'timesheet_id');
    }
}
