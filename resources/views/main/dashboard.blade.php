@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <p>Current Date and Time : <span id="clock">{{ now()->format('d F Y, H:i:s') }}</span></p>
                <p>IP Address : <span>{{ request()->ip() }}</span></p>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="container py-4">
                <div class="row justify-content-left">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Attendance Status</h5>
                            </div>
                            <div class="card-body">
                                @if($currentAttendance)
                                    <!-- Checked In Status -->
                                    <div class="alert alert-info">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><i class="fas fa-user-clock"></i> Status:</strong> <br>Checked In<br>
                                                <strong><i class="fas fa-clock"></i> Time:</strong><br>
                                                {{ $currentAttendance->check_in_time }}<br>
                                                <strong><i class="fas fa-hourglass-half"></i> Duration:</strong>
                                                <span id="currentDuration">00:00:00</span>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- Step 1: Show just the Check-Out Button -->
                                    <form id="checkoutStartForm" onsubmit="event.preventDefault(); showCheckoutDetails();"
                                        class="mb-0">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-lg">
                                            <i class="fas fa-sign-out-alt"></i> Check Out
                                        </button>
                                    </form>
                                    <!-- Step 2: Hidden full check-in form -->
                                    <div id="checkoutDetails" class="card mt-3 d-none">
                                        <div class="card-body">
                                            <form id="checkoutForm" method="POST" action="{{ route('attendance.checkout') }}"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <!-- Location Field -->
                                                <div class="form-group">
                                                    <label>Your Location*</label>
                                                    <button type="button" id="getLocationBtn" class="btn btn-primary btn-block">
                                                        <i class="fas fa-map-marker-alt mr-2"></i> Get My Current Location
                                                    </button>
                                                    <small id="locationStatus" class="form-text text-muted">Location not yet
                                                        captured</small>
                                                    <div id="manualLocation" class="mt-2" style="display: none;">
                                                        <div class="form-row">
                                                            <div class="col">
                                                                <input type="text" class="form-control" id="latitude"
                                                                    name="latitude" readonly required>
                                                            </div>
                                                            <div class="col">
                                                                <input type="text" class="form-control" id="longitude"
                                                                    name="longitude" readonly required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="locationError" class="invalid-feedback"></div>
                                                </div>

                                                <!-- Selfie Capture -->
                                                <div class="form-group">
                                                    <label>Selfie Verification*</label>
                                                    <div class="text-center mb-3">
                                                        <video id="selfieVideo" width="320" height="240" autoplay
                                                            class="d-none rounded border"></video>
                                                        <canvas id="selfieCanvas" width="320" height="240"
                                                            class="d-none rounded border"></canvas>
                                                        <img id="selfiePreview" src="#" alt="Your selfie"
                                                            class="d-none rounded border" width="320" height="240">
                                                    </div>
                                                    <div class="btn-group btn-block" role="group">
                                                        <button type="button" id="startCamera" class="btn btn-info">
                                                            <i class="fas fa-camera mr-2"></i> Start Camera
                                                        </button>
                                                        <button type="button" id="captureSelfie" class="btn btn-success d-none">
                                                            <i class="fas fa-camera-retro mr-2"></i> Capture
                                                        </button>
                                                        <button type="button" id="retakeSelfie" class="btn btn-warning d-none">
                                                            <i class="fas fa-redo mr-2"></i> Retake
                                                        </button>
                                                    </div>
                                                    <input type="file" id="uploadSelfie" name="selfie" accept="image/*"
                                                        capture="user" class="d-none">
                                                    <button type="button" id="uploadBtn"
                                                        class="btn btn-secondary btn-block mt-2">
                                                        <i class="fas fa-upload mr-2"></i> Upload Instead
                                                    </button>
                                                </div>

                                                <!-- Submit Button -->
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    <i class="fas fa-check-circle mr-2"></i> Submit Check-Out
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                @else
                                    <!-- Checked Out Status -->
                                    <div class="alert alert-warning">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><i class="fas fa-user-times"></i> Status:</strong> <br>Not Checked In
                                                @if($todayAttendance)
                                                    <br>
                                                    <strong>Last Check-out:</strong> <br>{{ $todayAttendance->check_out_time }}
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <!-- Step 1: Show just the Check-In Button -->
                                    <form id="checkinStartForm" onsubmit="event.preventDefault(); showCheckinDetails();"
                                        class="mb-0">
                                        <button type="submit" class="btn btn-success btn-lg btn-block">
                                            <i class="fas fa-sign-in-alt mr-2"></i> Check In
                                        </button>
                                    </form>

                                    <!-- Step 2: Hidden full check-in form -->
                                    <div id="checkinDetails" class="card mt-3 d-none">
                                        <div class="card-body">
                                            <form id="checkinForm" method="POST" action="{{ route('attendance.checkin') }}"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <div class="form-group">
                                                    <label for="work_mode">Work Mode*</label>
                                                    <select name="work_mode" id="work_mode" class="form-control" required>
                                                        <option value="office">Office</option>
                                                        <option value="remote">Anywhere / Remote</option>
                                                        <option value="paid_leave">Paid Leave</option>
                                                    </select>
                                                </div>

                                                <!-- Location Field -->
                                                <div class="form-group">
                                                    <label>Your Location*</label>
                                                    <button type="button" id="getLocationBtn" class="btn btn-primary btn-block">
                                                        <i class="fas fa-map-marker-alt mr-2"></i> Get My Current Location
                                                    </button>
                                                    <small id="locationStatus" class="form-text text-muted">Location not yet
                                                        captured</small>
                                                    <div id="manualLocation" class="mt-2" style="display: none;">
                                                        <div class="form-row">
                                                            <div class="col">
                                                                <input type="text" class="form-control" id="latitude"
                                                                    name="latitude" readonly required>
                                                            </div>
                                                            <div class="col">
                                                                <input type="text" class="form-control" id="longitude"
                                                                    name="longitude" readonly required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="locationError" class="invalid-feedback"></div>
                                                </div>

                                                <!-- Selfie Capture -->
                                                <div class="form-group">
                                                    <label>Selfie Verification*</label>
                                                    <div class="text-center mb-3">
                                                        <video id="selfieVideo" width="320" height="240" autoplay
                                                            class="d-none rounded border"></video>
                                                        <canvas id="selfieCanvas" width="320" height="240"
                                                            class="d-none rounded border"></canvas>
                                                        <img id="selfiePreview" src="#" alt="Your selfie"
                                                            class="d-none rounded border" width="320" height="240">
                                                    </div>
                                                    <div class="btn-group btn-block" role="group">
                                                        <button type="button" id="startCamera" class="btn btn-info">
                                                            <i class="fas fa-camera mr-2"></i> Start Camera
                                                        </button>
                                                        <button type="button" id="captureSelfie" class="btn btn-success d-none">
                                                            <i class="fas fa-camera-retro mr-2"></i> Capture
                                                        </button>
                                                        <button type="button" id="retakeSelfie" class="btn btn-warning d-none">
                                                            <i class="fas fa-redo mr-2"></i> Retake
                                                        </button>
                                                    </div>
                                                    <input type="file" id="uploadSelfie" name="selfie" accept="image/*"
                                                        capture="user" class="d-none">
                                                    <button type="button" id="uploadBtn"
                                                        class="btn btn-secondary btn-block mt-2">
                                                        <i class="fas fa-upload mr-2"></i> Upload Instead
                                                    </button>
                                                </div>

                                                <!-- Submit Button -->
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    <i class="fas fa-check-circle mr-2"></i> Submit Check-In
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($todayAttendance && !$currentAttendance)
                            <div class="card mt-4 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">Today's Attendance</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Check-in:</strong> {{ $todayAttendance->check_in_time }}</p>
                                    <p><strong>Check-out:</strong> {{ $todayAttendance->check_out_time ?? 'Pending' }}</p>
                                    <p><strong>Total Work Duration:</strong>
                                        {{ gmdate('H:i:s', $todayAttendance->work_duration) }}</p>
                                </div>
                            </div>
                        @else
                            <div class="card mt-4 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">Today's Attendance</h5>
                                </div>
                                <div class="card-body">
                                    <p>No attendance records for today.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Earnings (Annual)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">74%</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 74%"
                                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Requests</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showCheckinDetails() {
            document.getElementById('checkinStartForm').classList.add('d-none');
            document.getElementById('checkinDetails').classList.remove('d-none');
        }

        function showCheckoutDetails() {
            document.getElementById('checkoutStartForm').classList.add('d-none');
            document.getElementById('checkoutDetails').classList.remove('d-none');
        }
        // Update the clock every second
        setInterval(() => {
            const now = new Date();
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const day = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('clock').innerText =
                `${day} ${month} ${year}, ${hours}:${minutes}:${seconds}`;
        }, 1000);

        @if($currentAttendance)

            document.addEventListener("DOMContentLoaded", function () {
                const checkInTime = new Date('{{ $currentAttendance->check_in_date }}T{{ $currentAttendance->check_in_time }}');

                function updateDuration() {
                    const now = new Date();
                    const diff = Math.floor((now - checkInTime) / 1000);

                    const hours = String(Math.floor(diff / 3600)).padStart(2, '0');
                    const minutes = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
                    const seconds = String(diff % 60).padStart(2, '0');

                    document.getElementById('currentDuration').textContent =
                        `${hours}:${minutes}:${seconds}`;
                }

                updateDuration();
                setInterval(updateDuration, 1000);
            });

        @endif

                    // Location functionality
                    const locationStatus = document.getElementById('locationStatus');
        const getLocationBtn = document.getElementById('getLocationBtn');
        const manualLocation = document.getElementById('manualLocation');
        const locationError = document.getElementById('locationError');
        let hasLocation = false;

        // Get current location
        getLocationBtn.addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                        locationStatus.textContent = `Location captured: ${position.coords.latitude}, ${position.coords.longitude}`;
                        hasLocation = true;
                        locationError.textContent = '';
                        manualLocation.style.display = 'block';
                    },
                    (error) => {
                        locationError.textContent = `Error: ${error.message}`;
                        manualLocation.style.display = 'block';
                    }
                );
            } else {
                locationError.textContent = "Geolocation is not supported by your browser";
                manualLocation.style.display = 'block';
            }
        });


        // Camera functionality
        const video = document.getElementById('selfieVideo');
        const canvas = document.getElementById('selfieCanvas');
        const preview = document.getElementById('selfiePreview');
        const startBtn = document.getElementById('startCamera');
        const captureBtn = document.getElementById('captureSelfie');
        const retakeBtn = document.getElementById('retakeSelfie');
        const uploadBtn = document.getElementById('uploadBtn');
        const uploadInput = document.getElementById('uploadSelfie');
        let stream = null;

        // Start camera
        startBtn.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user' },
                    audio: false
                });
                video.srcObject = stream;
                video.classList.remove('d-none');
                startBtn.classList.add('d-none');
                captureBtn.classList.remove('d-none');
                uploadBtn.classList.add('d-none');
            } catch (err) {
                alert('Could not access the camera: ' + err.message);
            }
        });

        // Capture selfie
        captureBtn.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            // Stop camera stream
            stream.getTracks().forEach(track => track.stop());

            // Show preview
            preview.src = canvas.toDataURL('image/png');
            preview.classList.remove('d-none');
            video.classList.add('d-none');
            canvas.classList.add('d-none');
            captureBtn.classList.add('d-none');
            retakeBtn.classList.remove('d-none');
            uploadBtn.classList.remove('d-none');
        });

        // Retake selfie
        retakeBtn.addEventListener('click', () => {
            preview.classList.add('d-none');
            startBtn.click();
            retakeBtn.classList.add('d-none');
        });

        // Upload alternative
        uploadBtn.addEventListener('click', () => {
            uploadInput.click();
        });

        uploadInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    preview.src = event.target.result;
                    preview.classList.remove('d-none');
                    video.classList.add('d-none');
                    canvas.classList.add('d-none');
                    startBtn.classList.remove('d-none');
                    captureBtn.classList.add('d-none');
                    retakeBtn.classList.remove('d-none');
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Form submission
        document.getElementById('checkinForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Validate location
            if (!document.getElementById('latitude').value ||
                !document.getElementById('longitude').value) {
                document.getElementById('locationError').textContent = "Please provide your location";
                return;
            }

            // Validate selfie
            if (preview.classList.contains('d-none')) {
                alert('Please capture or upload a selfie');
                return;
            }

            const formData = new FormData();
            formData.append('latitude', document.getElementById('latitude').value);
            formData.append('longitude', document.getElementById('longitude').value);
            formData.append('selfie', preview.src);

            /*
            fetch('/attendance/check-in', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: formData
            })
            // Here you would typically send to your backend
            console.log("Submitting:", formData);
            */
            // Example: fetch('/api/checkin', { method: 'POST', body: JSON.stringify(formData) })

            //alert("Check-in submitted!");
            document.getElementById('checkinForm').submit();
        });

        // Form submission
        document.getElementById('checkoutForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Validate location
            if (!document.getElementById('latitude').value ||
                !document.getElementById('longitude').value) {
                document.getElementById('locationError').textContent = "Please provide your location";
                return;
            }

            // Validate selfie
            if (preview.classList.contains('d-none')) {
                alert('Please capture or upload a selfie');
                return;
            }

            const formData = new FormData();
            formData.append('work_mode', document.getElementById('work_mode').value);
            formData.append('latitude', document.getElementById('latitude').value);
            formData.append('longitude', document.getElementById('longitude').value);
            formData.append('selfie', preview.src);

            /*
            fetch('/attendance/check-in', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: formData
            })
            // Here you would typically send to your backend
            console.log("Submitting:", formData);
            */
            // Example: fetch('/api/checkin', { method: 'POST', body: JSON.stringify(formData) })

            //alert("Check-in submitted!");
            document.getElementById('checkoutForm').submit();
        });

    </script>



@endpush