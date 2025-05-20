@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-4">Attendance Records</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Attendance Data</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Check In Time</th>
                                <th>Check Out Time</th>
                                <th>Work Duration</th>
                                <th>Approval Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
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
                                </tr>

                                <tr class="attendance-details" style="display: none;">
                                    <td colspan="5">
                                        <!-- Work Mode (shared info) -->
                                        <p><strong>Work Mode:</strong> {{ ucfirst($attendance->work_mode ?? 'N/A') }}</p>
                                        @if ($attendance->approval_status !== 'pending' && $attendance->approver && $attendance->approver->profile)
                                            <p><strong>Reviewed by:</strong> {{ $attendance->approver->profile->fullname }}
                                                <br><strong>Review Date:</strong> {{ $attendance->approved_at }}
                                            </p>
                                        @endif
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
                                                        <img src="{{ asset($attendance->check_out_img_path) }}"
                                                            alt="Check-Out Selfie" width="100">
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

                </div>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                </div>

            </div>
        </div>
@endsection

    @push('scroll_top')
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    @endpush

    @push('scripts')

        <script src="vendor/jquery/jquery.min.js"></script>
        <!-- Page level plugins -->
        <script src="vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
        <script>

            $(document).ready(function () {
                $('#dataTable').DataTable();
            });
            // Toggle details row
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