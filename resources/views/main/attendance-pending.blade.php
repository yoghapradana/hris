@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-4">Pending Employee Attendance</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Employee Number</th>
                    <th>Employee Name</th>
                    <th>Check In Time</th>
                    <th>Check Out Time</th>
                    <th>Work Duration</th>
                    <th>Approval Status</th>
                    <th>Details</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $attendance)
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $attendance->user->employee_number ?? 'N/A' }}
                    </td>
                    <td>
                        {{ $attendance->user->profile->fullname ?? 'N/A'}}
                    </td>
                    <td>
                        {{ trim(($attendance->check_in_date ?? '') . ' ' . ($attendance->check_in_time ?? '')) ?: '-' }}
                    </td>
                    <td>
                        {{ trim(($attendance->check_out_date ?? '') . ' ' . ($attendance->check_out_time ?? '')) ?: '-' }}
                    </td>
                    <td>{{ $attendance->formatted_work_duration }}</td>
                    <td>
                        @if ($attendance->approval_status == 'approved')
                            <span class="badge badge-success">Approved</span>
                        @elseif ($attendance->approval_status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Rejected</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info toggle-details">Show Details</button>
                    </td>
                    <td>
                        <!-- Approve Button -->
                        <form method="POST" action="{{ route('attendance.approve', $attendance->id) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-sm">
                                Approve
                            </button>
                        </form>

                        <!-- Reject Button -->
                        <form method="POST" action="{{ route('attendance.reject', $attendance->id) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger btn-sm">
                                Reject
                            </button>
                        </form>
                    </td>
                    </tr>

                    <tr class="attendance-details" style="display: none;">
                        <td colspan="5">
                            <!-- Work Mode (shared info) -->
                            <p><strong>Work Mode:</strong> {{ ucfirst($attendance->work_mode ?? 'N/A') }}</p>

                            <div class="row">
                                <!-- Check-In Column -->
                                <div class="col-md-6">
                                    <h6><strong>Check-In</strong></h6>
                                    <p><strong>IP Address:</strong> {{ $attendance->check_in_ip ?? 'N/A' }}</p>
                                    <p><strong>Location:</strong><br>
                                        Latitude: {{ $attendance->check_in_latitude ?? 'N/A' }}<br>
                                        Longitude: {{ $attendance->check_in_longitude ?? 'N/A' }}
                                    </p>
                                    <p><strong>Selfie:</strong><br>
                                        @if ($attendance->check_in_img_path)
                                            <img src="{{ asset($attendance->check_in_img_path) }}" alt="Check-In Selfie"
                                                width="100">
                                        @else
                                            <span>N/A</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Check-Out Column -->
                                <div class="col-md-6">
                                    <h6><strong>Check-Out</strong></h6>
                                    <p><strong>IP Address:</strong> {{ $attendance->check_out_ip ?? 'N/A' }}</p>
                                    <p><strong>Location:</strong><br>
                                        Latitude: {{ $attendance->check_out_latitude ?? 'N/A' }}<br>
                                        Longitude: {{ $attendance->check_out_longitude ?? 'N/A' }}
                                    </p>
                                    <p><strong>Selfie:</strong><br>
                                        @if ($attendance->check_out_img_path)
                                            <img src="{{ asset($attendance->check_out_img_path) }}" alt="Check-Out Selfie"
                                                width="100">
                                        @else
                                            <span>N/A</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>

                    </tr>

                @endforeach
            </tbody>
        </table>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggles = document.querySelectorAll('.toggle-details');
            toggles.forEach(function (button) {
                button.addEventListener('click', function () {
                    const detailRow = this.closest('tr').nextElementSibling;
                    const isVisible = detailRow.style.display === 'table-row';
                    detailRow.style.display = isVisible ? 'none' : 'table-row';
                    this.textContent = isVisible ? 'Show Details' : 'Hide Details';
                });
            });
        });
    </script>
@endpush