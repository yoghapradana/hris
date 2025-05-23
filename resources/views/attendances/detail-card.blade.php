<div class="card shadow mb-4">
    <div class="card-body">
        <p><strong>Work Mode:</strong> {{ ucfirst($attendance->work_mode ?? 'N/A') }}</p>
        <p><strong>Work Duration:</strong> {{ $attendance->formatted_work_duration ?? 'N/A' }}</p>

        <p><strong>Approval Status:</strong>
            @if ($attendance->approval_status == 'approved')
                <span class="badge badge-success">Approved</span>
            @elseif ($attendance->approval_status == 'pending')
                <span class="badge badge-warning">Pending</span>
            @else
                <span class="badge badge-danger">Rejected</span>
            @endif
            @if ($attendance->approval_status !== 'pending')
                <p><strong>Reviewed by:</strong> {{ $attendance->approver->profile->fullname ?? 'N/A' }}<br>
                    <strong>Review Date:</strong> {{ $attendance->approved_at ?? 'N/A' }}<br>
                </p>
            @endif

        <div class="row">
            <!-- Check-In -->
            <div class="col-md-6">
                <h6><strong>Check-In</strong></h6>
                <p><strong>Date:</strong> {{ $attendance->check_in_date ?? 'N/A' }}</p>
                <p><strong>Time:</strong> {{ $attendance->check_in_time ?? 'N/A' }}</p>
                <p><strong>IP Address:</strong> {{ $attendance->check_in_ip ?? 'N/A' }}</p>
                <p><strong>Location:</strong><br>
                    Latitude: {{ $attendance->check_in_latitude ?? 'N/A' }}<br>
                    Longitude: {{ $attendance->check_in_longitude ?? 'N/A' }}
                </p>
                <p><strong>Selfie:</strong><br>
                    @if ($attendance->check_in_img_path)
                        <img src="{{ Storage::url($attendance->check_in_img_path) }}" alt="Check-In Selfie" width="100">
                    @else
                        <span>N/A</span>
                    @endif
                </p>
            </div>

            <!-- Check-Out -->
            <div class="col-md-6">
                <h6><strong>Check-Out</strong></h6>
                <p><strong>Date:</strong> {{ $attendance->check_out_date ?? 'N/A' }}</p>
                <p><strong>Time:</strong> {{ $attendance->check_out_time ?? 'N/A' }}</p>
                <p><strong>IP Address:</strong> {{ $attendance->check_out_ip ?? 'N/A' }}</p>
                <p><strong>Location:</strong><br>
                    Latitude: {{ $attendance->check_out_latitude ?? 'N/A' }}<br>
                    Longitude: {{ $attendance->check_out_longitude ?? 'N/A' }}
                </p>
                <p><strong>Selfie:</strong><br>
                    @if ($attendance->check_out_img_path)
                        <img src="{{ Storage::url($attendance->check_out_img_path) }}" alt="Check-Out Selfie" width="100">
                    @else
                        <span>N/A</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>