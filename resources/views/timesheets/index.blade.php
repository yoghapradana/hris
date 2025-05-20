@extends('layouts.app')

@section('title', 'Timesheets')

@section('content')
    <div class="container">
        <h1 class="mb-4">Timesheets</h1>

        @if($timesheets->isEmpty())
            <p>No timesheets found.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Time</th>
                        <th>Number of Work</th>
                        <th>Review Status</th>
                        <th>Reviewer</th>
                        <th>Review Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($timesheets as $timesheet)
                        <!-- Main Row -->
                        <tr>
                            <td>{{ $timesheet->date ? $timesheet->date : '-' }}</td>
                            <td>{{ $timesheet->total_time ?? '-' }}</td>
                            <td>{{ $timesheet->number_of_work ?? 0 }}</td>
                            <td>
                                @if ($timesheet->review_status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif ($timesheet->review_status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if ($timesheet->reviewer && $timesheet->reviewer->profile)
                                    {{ $timesheet->reviewer->profile->fullname }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if ($timesheet->review_date)
                                    {{ $timesheet->review_date }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td><button class="btn btn-sm btn-outline-info toggle-details">Details</button></td>
                        </tr>

                        <!-- Hidden Detail Row -->
                        <tr class="timesheet-details" style="display: none;">
                            <td colspan="7">
                                <strong>Work Entries:</strong>
                                <ul>
                                    @foreach ($timesheet->details as $detail)
                                        <li>
                                            <strong>Time:</strong> {{ $detail->time_start }} - {{ $detail->time_end }} |
                                            <strong>Duration:</strong> {{ $detail->total_time }} |
                                            <strong>Code:</strong> {{ $detail->job_code }}<br>
                                            <strong>Description:</strong> {{ $detail->job_descriptions }}<br>
                                            <strong>Remark:</strong> {{ $detail->remark ?? 'N/A' }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
            {{-- Add pagination if needed --}}

        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-details').forEach(button => {
                button.addEventListener('click', function () {
                    const mainRow = this.closest('tr');
                    const detailRow = mainRow.nextElementSibling;
                    if (detailRow && detailRow.classList.contains('timesheet-details')) {
                        detailRow.style.display = detailRow.style.display === 'none' ? '' : 'none';
                    }
                });
            });
        });
    </script>
@endpush