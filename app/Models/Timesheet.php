<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'total_time',
        'number_of_work',
        'review_status',
        'reviewer_id',
        'review_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function details()
    {
        return $this->hasMany(TimesheetDetail::class, 'timesheet_id', 'id');
    }
}
