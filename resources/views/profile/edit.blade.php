@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Edit Profile</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @php $profile = $user->profile; @endphp

            <!-- Profile Picture Section -->
            <div class="mb-4 text-center">
                <label class="form-label d-block">Profile Picture</label>
                <div class="mb-2">
                    @if ($profile->img_profile_path)
                        <img src="{{ Storage::url($profile->img_profile_path) }}" alt="Profile Picture" class="rounded img-thumbnail"
                            width="120" style="height: auto;" id="userPicture">
                    @else
                        <img src="{{ Storage::url(config('custom.img_user.profile.m.default')) }}" alt="Default Avatar" class="rounded img-thumbnail"
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
                <input type="text" class="form-control" value="{{ $user->username }}" >
                
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" value="{{ $user->email }}" >
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
                <input type="text" class="form-control" value="{{ $profile->entry_date ?? 'Not set' }}">
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