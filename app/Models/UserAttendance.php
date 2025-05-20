<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAttendance extends Model
{
    const MODE_OFFICE = 'office';
    const MODE_REMOTE = 'remote';
    const MODE_PAID_LEAVE = 'paid_leave';

    protected $table = 'user_attendances';

    protected $fillable = [
        'user_id',
        'check_in_date',
        'check_in_time',
        'check_in_img_path',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_ip',
        'check_out_date',
        'check_out_time',
        'check_out_img_path',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_ip',
        'work_duration', // Will store seconds as integer
        'approval_status',
        'approver_id',
        'approval_timestamp',
        'work_mode'
    ];

    protected $casts = [
        'check_in_date',
        'check_out_date',
        'check_in_latitude' => 'decimal:8',
        'check_in_longitude' => 'decimal:8',
        'check_out_latitude' => 'decimal:8',
        'check_out_longitude' => 'decimal:8',
        'approval_timestamp' => 'datetime',
        'work_duration' => 'integer' // Cast to integer for seconds
    ];

    protected $attributes = [
        'approval_status' => 'pending',
        'work_duration' => 0 // Default to 0 seconds
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Helper method to calculate work duration in hours
     */
    public function getWorkDurationInHoursAttribute(): float
    {
        return $this->work_duration / 3600;
    }

    /**
     * Helper method to format work duration as HH:MM:SS
     */
    // In your UserAttendance model
    public function getFormattedWorkDurationAttribute(): string
    {
        return $this->work_duration ? gmdate('H:i:s', $this->work_duration) : '-';
    }
}
