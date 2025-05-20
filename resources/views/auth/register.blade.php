@extends('layouts.auth')

@section('title', 'Register Page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block bg-register-image">
                            some image
                        </div>
                        <div class="col-lg-7">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                </div>

                                <form method="POST" action="{{ route('register.post') }}" class="user">
                                    @csrf

                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="username"
                                            name="username" placeholder="User Name">
                                        @error('username')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" id="email" name="email"
                                            placeholder="Email Address">
                                        @error('email')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password"
                                            name="password" placeholder="Password">
                                        @error('password')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                            id="password_confirmation" name="password_confirmation"
                                            placeholder="Repeat Password">
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Register
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection