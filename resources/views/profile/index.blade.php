@extends('layouts.app')
@section('title', 'Employee List')

@push('styles')
    <link href={{ asset("vendor/datatables/dataTables.bootstrap4.min.css") }} rel="stylesheet">
@endpush


@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Employee List</h1>
        <div class="card shadow mb-4">

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Employee Number</th>
                                <th>Full Name</th>
                                <th>Division</th>
                                <th>Position</th>
                                <th>Entry Date</th>
                                <th>Details</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $user->employee_number ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $user->profile->fullname ?? 'N/A'}}
                                    </td>
                                    <td>
                                        {{ $user->profile->division ?? 'N/A'}}
                                    </td>
                                    <td>
                                        {{ $user->profile->position ?? 'N/A'}}
                                    </td>
                                    <td>
                                        {{ $user->profile->entry_date ? \Carbon\Carbon::parse($user->profile->entry_date)->format('d-m-Y') : 'N/A'}}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info toggle-details"
                                            data-user-id="{{ $user->id }}">
                                            Details
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


    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="profileModalBody">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.toggle-details', function (){
                var userId = $(this).data('user-id');

                // Show loading spinner
                $('#profileModalBody').html(
                    '<div class="text-center">' +
                    '<div class="spinner-border text-primary" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '</div>'
                );

                // Show modal
                $('#profileModal').modal('show');

                // Load profile via AJAX
                $.ajax({
                    url: '/profile/' + userId,
                    method: 'GET',
                    success: function (data) {
                        $('#profileModalBody').html(data);
                    },
                    error: function () {
                        $('#profileModalBody').html(
                            '<div class="alert alert-danger">Failed to load profile.</div>'
                        );
                    }
                });
            });

            // Initialize DataTables
            $('#dataTable').DataTable();
        });


    </script>
@endpush