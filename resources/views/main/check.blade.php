@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Attendance Check-In</h6>
            </div>
            <div class="ca  rd-body">
                <form id="checkinForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Name Field 
                                <div class="form-group">
                                    <label for="name">Full Name*</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                            -->

                    <!-- Location Field -->
                    <div class="form-group">
                        <label>Your Location*</label>
                        <button type="button" id="getLocationBtn" class="btn btn-primary btn-block">
                            <i class="fas fa-map-marker-alt mr-2"></i> Get My Current Location
                        </button>
                        <small id="locationStatus" class="form-text text-muted">Location not yet captured</small>
                        <div id="manualLocation" class="mt-2" style="display: none;">
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" class="form-control" id="latitude" placeholder="Latitude" readonly
                                        required>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" id="longitude" placeholder="Longitude" readonly
                                        required>
                                </div>
                            </div>
                        </div>
                        <div id="locationError" class="invalid-feedback"></div>
                    </div>

                    <!-- Selfie Capture -->
                    <div class="form-group">
                        <label>Selfie Verification*</label>
                        <div class="text-center mb-3">
                            <video id="selfieVideo" width="320" height="240" autoplay class="d-none rounded border"></video>
                            <canvas id="selfieCanvas" width="320" height="240" class="d-none rounded border"></canvas>
                            <img id="selfiePreview" src="#" alt="Your selfie" class="d-none rounded border" width="320"
                                height="240">
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
                        <input type="file" id="uploadSelfie" accept="image/*" capture="user" class="d-none">
                        <button type="button" id="uploadBtn" class="btn btn-secondary btn-block mt-2">
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
    </div>
@endsection

@push('scripts')
    <script>

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

            fetch('/attendance/check-in', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: formData
            })
            // Here you would typically send to your backend
            console.log("Submitting:", formData);
            // Example: fetch('/api/checkin', { method: 'POST', body: JSON.stringify(formData) })

            alert("Check-in submitted!");
        });
    </script>
@endpush