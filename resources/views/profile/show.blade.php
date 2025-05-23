@extends('layouts.app')
@section('title', 'Profile Page')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Profile Page</h1>
        @include('profile.show-card', ['user' => $user])
    </div>

@endsection