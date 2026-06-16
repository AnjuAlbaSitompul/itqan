@extends('layouts.app')

@section('content')
    <div class="container mt-4 mb-5 max-w-md mx-auto" style="max-width: 600px;">

        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Dashboard</h2>
                <p class="text-muted small mb-0">Track your daily activities</p>
            </div>
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                style="width: 45px; height: 45px;">
                <span class="fs-5">✨</span>
            </div>
        </div>

        <div class="nav-wrapper mb-4">
            <ul class="nav nav-pills nav-fill custom-tabs p-1 rounded-pill bg-light shadow-inner" id="activityTabs"
                role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill d-flex align-items-center justify-content-center gap-2"
                        id="jogging-tab" data-bs-toggle="pill" data-bs-target="#jogging" type="button" role="tab"
                        aria-controls="jogging" aria-selected="true">
                        <span>🏃‍♂️</span> <span class="d-none d-sm-inline">Jogging</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill d-flex align-items-center justify-content-center gap-2"
                        id="time-tab" data-bs-toggle="pill" data-bs-target="#time-record" type="button" role="tab"
                        aria-controls="time-record" aria-selected="false">
                        <span>📸</span> <span class="d-none d-sm-inline">Evidence</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill d-flex align-items-center justify-content-center gap-2"
                        id="book-tab" data-bs-toggle="pill" data-bs-target="#book-report" type="button" role="tab"
                        aria-controls="book-report" aria-selected="false">
                        <span>📚</span> <span class="d-none d-sm-inline">Reading</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="activityTabsContent">

            <div class="tab-pane fade show active" id="jogging" role="tabpanel" aria-labelledby="jogging-tab">
                <div class="card custom-card border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="badge bg-light text-primary mb-3 px-3 py-2 rounded-pill shadow-sm">GPS Tracking Active
                        </div>

                        <div
                            class="tracking-circle mx-auto d-flex flex-column justify-content-center align-items-center shadow-inner mb-4">
                            <div class="display-3 fw-bolder text-dark mb-0" id="distanceLabel" style="line-height: 1;">0.00
                            </div>
                            <div class="text-muted fw-semibold">Kilometers</div>
                        </div>

                        <div class="h2 fw-light text-secondary mb-3 timer-font" id="stopwatch">00:00:00</div>
                        <div id="gpsStatus"
                            class="small fw-semibold text-white mb-4 px-3 py-2 bg-warning bg-opacity-10 rounded-pill d-inline-block">
                            Waiting for signal...</div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow-sm" id="startJogging">
                                <span class="fs-5 me-2">▶️</span> Start Run
                            </button>
                            <button class="btn btn-danger btn-lg rounded-pill py-3 fw-bold shadow-sm d-none"
                                id="stopJogging">
                                <span class="fs-5 me-2">⏹️</span> Stop & Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="time-record" role="tabpanel" aria-labelledby="time-tab">
                <div class="card custom-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Report Evidence</h5>
                        <form id="timeRecordForm">

                            <div class="upload-zone text-center p-4 mb-4 rounded-4 bg-light border border-2 border-dashed">
                                <label class="form-label d-block cursor-pointer mb-0">
                                    <div class="fs-1 mb-2">📷</div>
                                    <span class="fw-semibold text-primary d-block mb-1">Tap to Capture Image</span>
                                    <small class="text-muted">Requires camera access</small>
                                    <input type="file" class="d-none" accept="image/*" capture="environment" required>
                                </label>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="text" id="deviceTime" class="form-control rounded-3" readonly
                                    placeholder="Device Time">
                                <label for="deviceTime">Recorded Device Time</label>
                                <button
                                    class="btn btn-sm btn-primary position-absolute top-50 end-0 translate-middle-y me-2 rounded-pill px-3"
                                    type="button" id="recordTimeBtn">Get Time</button>
                            </div>

                            <button type="submit"
                                class="btn btn-primary btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm">Submit
                                Report</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="book-report" role="tabpanel" aria-labelledby="book-tab">

                <div class="card custom-card border-0 shadow-sm mb-4" id="proposeBookSection">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-warning bg-opacity-10 p-2 rounded text-warning me-3">📖</div>
                            <h5 class="fw-bold mb-0">Propose a Book</h5>
                        </div>

                        <form>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-3" id="bookTitle" placeholder="Book Title">
                                <label for="bookTitle">Book Title</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control rounded-3" id="authorName" placeholder="Author">
                                <label for="authorName">Author</label>
                            </div>
                            <button type="button"
                                class="btn btn-warning btn-lg w-100 rounded-pill py-3 fw-bold text-dark shadow-sm"
                                id="submitProposalBtn">Request Approval</button>
                        </form>
                    </div>
                </div>

                <div class="card custom-card border-0 shadow-sm" id="dailyLogSection" style="display: none;">
                    <div class="card-header bg-success bg-gradient text-white p-3 border-0 rounded-top-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="small opacity-75 d-block">Active Book</span>
                                <strong class="fs-5" id="activeBookTitle">Atomic Habits</strong>
                            </div>
                            <div class="text-end">
                                <span class="small opacity-75 d-block">Due Date</span>
                                <strong id="dueDate">Dec 31</strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 text-success">Daily Log</h6>
                        <form>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control rounded-3" id="pageFrom" min="1"
                                            placeholder="From" required>
                                        <label for="pageFrom">Page From</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control rounded-3" id="pageTo" min="1"
                                            placeholder="To" required>
                                        <label for="pageTo">Page To</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-4">
                                <textarea class="form-control rounded-3" id="summary" placeholder="Summary"
                                    style="height: 120px" required></textarea>
                                <label for="summary">Summary / Key Takeaways</label>
                            </div>
                            <button type="submit"
                                class="btn btn-success btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm">Save
                                Progress</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Modern Typography & Base Styling */
        body {
            background-color: #f8f9fa;
            /* Soft background */
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Custom Card Styling */
        .custom-card {
            border-radius: 1.25rem;
            transition: transform 0.2s ease-in-out;
        }

        .card-header.rounded-top-4 {
            border-top-left-radius: 1.25rem !important;
            border-top-right-radius: 1.25rem !important;
        }

        /* Segmented Control (Tabs) */
        .custom-tabs {
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .custom-tabs .nav-link {
            color: #6c757d;
            font-weight: 600;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .custom-tabs .nav-link.active {
            background-color: #ffffff;
            color: #0d6efd;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Jogging Circular UI */
        .tracking-circle {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: #ffffff;
            border: 8px solid #e9ecef;
            box-shadow: inset 0 4px 10px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .tracking-circle::after {
            content: '';
            position: absolute;
            top: -8px;
            left: -8px;
            right: -8px;
            bottom: -8px;
            border-radius: 50%;
            border: 8px solid #0d6efd;
            clip-path: polygon(50% 0%, 100% 0, 100% 50%, 50% 50%);
            /* Simulates a progress ring */
            opacity: 0.2;
        }

        .timer-font {
            font-family: 'Courier New', Courier, monospace;
            letter-spacing: -1px;
        }

        /* Forms & Inputs */
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .border-dashed {
            border-style: dashed !important;
            border-color: #dee2e6 !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        /* Anti-Dev Mode specific to jogging */
        #jogging {
            user-select: none;
            -webkit-user-select: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // --- 1. JOGGING TRACKER ---
            let timer;
            let seconds = 0;
            let watchId = null;
            let totalDistanceKm = 0;
            let lastPosition = null;
            let lastTime = null;
            const MAX_SPEED_KMH = 30;

            function formatTime(sec) {
                const h = Math.floor(sec / 3600).toString().padStart(2, '0');
                const m = Math.floor((sec % 3600) / 60).toString().padStart(2, '0');
                const s = Math.floor(sec % 60).toString().padStart(2, '0');
                return `${h}:${m}:${s}`;
            }

            function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
                var R = 6371;
                var dLat = deg2rad(lat2 - lat1);
                var dLon = deg2rad(lon2 - lon1);
                var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            function deg2rad(deg) { return deg * (Math.PI / 180); }

            function handleLocationUpdate(position) {
                const currentLat = position.coords.latitude;
                const currentLon = position.coords.longitude;
                const accuracy = position.coords.accuracy;
                const currentTime = position.timestamp;

                // UI Updates for status
                let statusEl = $('#gpsStatus');
                statusEl.removeClass('bg-warning bg-danger text-warning text-danger').addClass('bg-success bg-opacity-10 text-success');
                statusEl.text(`Tracking Active (Accuracy: ${Math.round(accuracy)}m)`);

                if (accuracy > 50) {
                    statusEl.removeClass('bg-success text-success').addClass('bg-warning bg-opacity-10 text-warning');
                    statusEl.text(`Calibrating... (${Math.round(accuracy)}m)`);
                    return;
                }

                if (lastPosition !== null) {
                    let distanceDelta = getDistanceFromLatLonInKm(lastPosition.lat, lastPosition.lon, currentLat, currentLon);
                    let timeDeltaHours = (currentTime - lastTime) / (1000 * 60 * 60);

                    if (timeDeltaHours > 0) {
                        let speedKmh = distanceDelta / timeDeltaHours;
                        if (speedKmh > MAX_SPEED_KMH) {
                            statusEl.removeClass('bg-success text-success').addClass('bg-danger bg-opacity-10 text-danger');
                            statusEl.text("Speed warning. Point ignored.");
                            return;
                        }
                    }
                    totalDistanceKm += distanceDelta;
                    $('#distanceLabel').text(totalDistanceKm.toFixed(2));
                }

                lastPosition = { lat: currentLat, lon: currentLon };
                lastTime = currentTime;
            }

            function locationError(err) {
                $('#gpsStatus').removeClass('bg-warning bg-success text-warning text-success')
                    .addClass('bg-danger bg-opacity-10 text-danger')
                    .text(`GPS Error: ${err.message}`);
            }

            $('#startJogging').click(function () {
                if (!navigator.geolocation) {
                    alert("Geolocation is not supported by your browser.");
                    return;
                }

                // Toggle Buttons smoothly
                $(this).addClass('d-none');
                $('#stopJogging').removeClass('d-none');

                seconds = 0; totalDistanceKm = 0; lastPosition = null;
                $('#stopwatch').text("00:00:00");
                $('#distanceLabel').text("0.00");
                $('#gpsStatus').removeClass('bg-danger bg-success text-danger text-success')
                    .addClass('bg-warning bg-opacity-10 text-warning')
                    .text("Acquiring Satellite...");

                timer = setInterval(function () {
                    seconds++;
                    $('#stopwatch').text(formatTime(seconds));
                }, 1000);

                watchId = navigator.geolocation.watchPosition(handleLocationUpdate, locationError, {
                    enableHighAccuracy: true, maximumAge: 0, timeout: 10000
                });
            });

            $('#stopJogging').click(function () {
                $(this).addClass('d-none');
                $('#startJogging').removeClass('d-none');

                clearInterval(timer);
                if (watchId !== null) navigator.geolocation.clearWatch(watchId);

                $('#gpsStatus').removeClass('bg-success bg-warning text-success text-warning')
                    .addClass('bg-secondary bg-opacity-10 text-secondary')
                    .text("Run Finished & Saved.");
            });

            // --- 2. TIME RECORD ---
            $('#recordTimeBtn').click(function () {
                const now = new Date();
                const formattedTime = now.getFullYear() + "-" +
                    ("0" + (now.getMonth() + 1)).slice(-2) + "-" +
                    ("0" + now.getDate()).slice(-2) + " " +
                    ("0" + now.getHours()).slice(-2) + ":" +
                    ("0" + now.getMinutes()).slice(-2);
                $('#deviceTime').val(formattedTime);
            });

            // --- 3. BOOK REPORT MOCKUP ---
            $('#submitProposalBtn').click(function () {
                let btn = $(this);
                btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending...').prop('disabled', true);

                // Simulating approval
                setTimeout(function () {
                    $('#proposeBookSection').slideUp(300, function () {
                        $('#dailyLogSection').slideDown(300);
                    });
                }, 1500);
            });
        });
    </script>
@endpush