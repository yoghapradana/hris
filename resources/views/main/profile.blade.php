@extends('layouts.app')

@section('content')
    <div class="container mt-5" style="max-width: 600px;">
        <h3 class="mb-4">My Profile</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Picture Section -->
            <div class="mb-4 text-center">
                <label class="form-label d-block">Profile Picture</label>
                <div class="mb-2">
                    @if ($profile->img_pic_path)
                        <img src="{{ asset($profile->img_pic_path) }}" alt="Profile Picture" class="rounded img-thumbnail"
                            width="120" style="height: auto;" id="userPicture">
                    @else
                        <img src="{{ asset('img/undraw_profile.svg') }}" alt="Default Avatar" class="rounded img-thumbnail"
                            width="120" style="height: auto;" id="userPicture">
                    @endif
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Upload New Profile Picture</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                        onchange="previewImage(event)">
                </div>
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                <small class="form-text text-muted">Username can only be changed by HR.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" value="{{ $user->email }}" disabled>
            </div>

            <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullname" name="fullname"
                    value="{{ old('fullname', $profile->fullname) }}">
            </div>

            <div class="mb-3">
                <label for="division" class="form-label">Division</label>
                <input type="text" class="form-control" id="division" name="division"
                    value="{{ old('division', $profile->division) }}">
            </div>

            <div class="mb-3">
                <label for="position" class="form-label">Position</label>
                <input type="text" class="form-control" id="position" name="position"
                    value="{{ old('position', $profile->position) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Entry Date</label>
                <input type="text" class="form-control" value="{{ $profile->entry_date ?? 'Not set' }}" disabled>
                <small class="form-text text-muted">This field is managed by HR.</small>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('userPicture');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush