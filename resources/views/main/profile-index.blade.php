@extends('layouts.app')
@section('title', 'Profile Page')
@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Profile Page</h1>


        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3 text-left">
                        <img src="{{$user->profile->img_pic_path}}" class="img-fluid rounded mb-3" alt="Profile Photo">
                        <h5 class="font-weight-bold">{{ $user->profile->fullname }}</h5>
                        <p class="text-muted">Software Engineer</p>
                        <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                        <p><i class="fas fa-phone"></i> +62 812 3456 7890</p>
                    </div>
                    <div class="col-md-9">
                        <h5 class="text-primary font-weight-bold">Personal Information</h5>
                        <p>I am a passionate software engineer with 5+ years of experience in full-stack
                            development. Skilled in building scalable web applications and creating
                            efficient backend systems.</p>

                        <h5 class="text-primary font-weight-bold mt-4">Work Experience</h5>
                        <ul class="list-unstyled">
                            <li>
                                <strong>Senior Developer</strong> - ABC Corp (2020–Present)
                                <br><small>Led the team in building internal tools and dashboards using
                                    Laravel and Vue.js.</small>
                            </li>
                            <li class="mt-3">
                                <strong>Junior Developer</strong> - XYZ Ltd (2018–2020)
                                <br><small>Developed REST APIs and contributed to mobile app backend
                                    infrastructure.</small>
                            </li>
                        </ul>

                        <h5 class="text-primary font-weight-bold mt-4">Education</h5>
                        <ul class="list-unstyled">
                            <li>
                                <strong>Bachelor of Computer Science</strong> - Universitas Indonesia
                                (2014–2018)
                            </li>
                        </ul>

                        <h5 class="text-primary font-weight-bold mt-4">Skills</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li>Laravel / PHP</li>
                                    <li>MySQL / PostgreSQL</li>
                                    <li>JavaScript / Vue.js</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li>HTML / CSS / Bootstrap</li>
                                    <li>Git / GitHub</li>
                                    <li>REST API Development</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection