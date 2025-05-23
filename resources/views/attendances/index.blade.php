@extends('layouts.app')
@section('title', 'Attendance Records')

@push('styles')
    <link href={{ asset("vendor/datatables/dataTables.bootstrap4.min.css") }} rel="stylesheet">
@endpush


@section('content')
    <div class="container">
        <h1 class="mt-4">Attendance Records</h1>
        <div class="card shadow mb-4">
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
                                <tr>
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
                                        <button type="button" class="btn btn-sm btn-info view-attendance-details"
                                            data-id="{{ $attendance->id }}" data-toggle="modal" data-target="#attendanceModal">
                                            View Details
                                        </button>
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
    </div>

    <!-- Attendance Details Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendance Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="attendanceModalBody">
                    <div class="text-center p-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
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

    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>

        $(document).ready(function () {
            $('#dataTable').DataTable();

            $(document).on('click', '.view-attendance-details', function () {
                var attendanceId = $(this).data('id');

                // Show loading spinner
                $('#attendanceModalBody').html(
                    '<div class="text-center">' +
                    '<div class="spinner-border text-primary" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '</div>'
                );

                // Show modal
                $('#attendanceModal').modal('show');

                $.ajax({
                    url: '/attendance/' + attendanceId,
                    type: 'GET',
                    success: function (data) {
                        $('#attendanceModalBody').html(data);
                    },
                    error: function () {
                        $('#attendanceModalBody').html('<p class="text-danger text-center">Failed to load attendance details.</p>');
                    }
                });
            });
        });
    </script>
@endpush