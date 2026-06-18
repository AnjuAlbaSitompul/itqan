@extends('layouts.app')

@section('content')
    <div class="container mt-4 mb-5 max-w-md mx-auto" style="max-width: 600px;">

        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Individual Development Plan</h2>
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
                        id="prayer-tab" data-bs-toggle="pill" data-bs-target="#prayer-report" type="button" role="tab"
                        aria-controls="prayer-report" aria-selected="false">
                        <span>🌙</span> <span class="d-none d-sm-inline">Shalat</span>
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
                            class="small fw-semibold text-warning mb-4 px-3 py-2 bg-warning bg-opacity-10 rounded-pill d-inline-block">
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

            <div class="tab-pane fade" id="prayer-report" role="tabpanel" aria-labelledby="prayer-tab">
                <div class="card custom-card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Lapor Shalat</h5>
                        <form id="prayerForm">
                            <div class="mb-4">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="prayerType" id="tahajjud"
                                            value="Tahajjud" checked>
                                        <label class="btn btn-outline-primary w-100 rounded-3 py-2 fw-semibold"
                                            for="tahajjud">Tahajjud</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="prayerType" id="subuh" value="Subuh">
                                        <label class="btn btn-outline-primary w-100 rounded-3 py-2 fw-semibold"
                                            for="subuh">Subuh</label>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded-4 mb-4 border shadow-sm" id="timeValidationBox">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                    <span class="small text-muted fw-bold">Jadwal Lapor:</span>
                                    <strong class="small text-primary" id="scheduleWindowDisplay">--:-- s/d --:--</strong>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small text-muted">Device Time:</span>
                                    <strong class="small" id="deviceTimeDisplay">--:--:--</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-muted">Server Time:</span>
                                    <strong class="small text-dark" id="serverTimeDisplay">Fetching...</strong>
                                </div>
                                <hr class="my-2">
                                <div id="timeStatusMsg" class="small text-center fw-semibold text-warning">
                                    Menyinkronkan waktu dengan server...
                                </div>
                            </div>

                            <button type="submit" id="submitPrayerBtn"
                                class="btn btn-primary btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm" disabled>
                                Submit Laporan
                            </button>
                        </form>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                    <h6 class="fw-bold mb-0">Riwayat Laporan</h6>
                    <select class="form-select form-select-sm w-auto rounded-pill shadow-sm" id="monthFilter">
                        <option value="all">Semua Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>

                <div class="accordion custom-accordion" id="prayerHistoryAccordion">
                    @forelse($prayerReports as $index => $report)
                        @php
                            $reportDate = \Carbon\Carbon::parse($report->reported_at);
                            $month = $reportDate->format('n'); // Bulan tanpa 0 di depan (1-12)
                        @endphp
                        <div class="accordion-item border-0 mb-2 shadow-sm rounded-4" data-month="{{ $month }}">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed rounded-4 fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#history{{ $index }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center me-3">
                                        <span>{{ $report->prayer_type }}</span>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill">
                                            {{ $reportDate->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                </button>
                            </h2>
                            <div id="history{{ $index }}" class="accordion-collapse collapse"
                                data-bs-parent="#prayerHistoryAccordion">
                                <div class="accordion-body text-muted small pt-0">
                                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                        <span>Waktu Lapor:</span>
                                        <strong class="text-dark">{{ $reportDate->format('H:i') }} WIB</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Status Validasi:</span>
                                        <span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Sesuai
                                            Server</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted small py-3" id="emptyHistory">Belum ada riwayat laporan.</div>
                    @endforelse
                </div>

            </div>

            <div class="tab-pane fade" id="book-report" role="tabpanel" aria-labelledby="book-tab">

                @if(!$activeBook)
                    <div class="card custom-card border-0 shadow-sm mb-4" id="proposeBookSection">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-warning bg-opacity-10 p-2 rounded text-warning me-3">📖</div>
                                <h5 class="fw-bold mb-0">Propose a Book</h5>
                            </div>
                            <form id="bookProposalForm">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control rounded-3" id="bookTitle" name="title"
                                        placeholder="Book Title" required>
                                    <label for="bookTitle">Book Title</label>
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="text" class="form-control rounded-3" id="authorName" name="author"
                                        placeholder="Author" required>
                                    <label for="authorName">Author</label>
                                </div>
                                <button type="submit"
                                    class="btn btn-warning btn-lg w-100 rounded-pill py-3 fw-bold text-dark shadow-sm"
                                    id="submitProposalBtn">Request Approval</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="card custom-card border-0 shadow-sm" id="dailyLogSection">
                        <div
                            class="card-header {{ $activeBook->status == 'approved' ? 'bg-success' : 'bg-secondary' }} bg-gradient text-white p-3 border-0 rounded-top-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="small opacity-75 d-block">Active Book
                                        ({{ ucfirst($activeBook->status) }})</span>
                                    <strong class="fs-5" id="activeBookTitle">{{ $activeBook->title }}</strong>
                                </div>
                                <div class="text-end">
                                    <span class="small opacity-75 d-block">Due Date</span>
                                    <strong
                                        id="dueDate">{{ $activeBook->due_date ? \Carbon\Carbon::parse($activeBook->due_date)->format('d M') : 'Pending' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @if($activeBook->status == 'pending')
                                <div class="text-center py-4">
                                    <h5 class="text-muted fw-bold">Menunggu Persetujuan</h5>
                                    <p class="small text-muted">Anda tidak dapat mengisi log harian sebelum proposal disetujui oleh
                                        atasan.</p>
                                </div>
                            @elseif($activeBook->status == 'approved')
                                <h6 class="fw-bold mb-3 text-success">Daily Log</h6>
                                <form id="dailyLogForm">
                                    <input type="hidden" id="bookProposalId" value="{{ $activeBook->id }}">
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
                                    <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm"
                                        id="submitLogBtn">
                                        Save Progress
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .custom-card {
            border-radius: 1.25rem;
            transition: transform 0.2s ease-in-out;
        }

        .card-header.rounded-top-4 {
            border-top-left-radius: 1.25rem !important;
            border-top-right-radius: 1.25rem !important;
        }

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
            opacity: 0.2;
        }

        .timer-font {
            font-family: 'Courier New', Courier, monospace;
            letter-spacing: -1px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        #jogging {
            user-select: none;
            -webkit-user-select: none;
        }

        .custom-accordion .accordion-item {
            background-color: #fff;
            overflow: hidden;
        }

        .custom-accordion .accordion-button {
            background-color: #fff;
            box-shadow: none !important;
            padding: 1rem 1.25rem;
        }

        .custom-accordion .accordion-button:not(.collapsed) {
            color: #0d6efd;
            background-color: #f8fbff;
        }

        .btn-outline-primary:hover {
            color: white !important;
        }

        .btn-check:checked+.btn-outline-primary {
            color: white;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {

            // ==========================================
            // 1. JOGGING TRACKER
            // ==========================================
            let timer; let seconds = 0; let watchId = null; let totalDistanceKm = 0; let lastPosition = null; let lastTime = null; const MAX_SPEED_KMH = 30;
            function formatTime(sec) { const h = Math.floor(sec / 3600).toString().padStart(2, '0'); const m = Math.floor((sec % 3600) / 60).toString().padStart(2, '0'); const s = Math.floor(sec % 60).toString().padStart(2, '0'); return `${h}:${m}:${s}`; }
            function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) { var R = 6371; var dLat = deg2rad(lat2 - lat1); var dLon = deg2rad(lon2 - lon1); var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon / 2) * Math.sin(dLon / 2); var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)); return R * c; }
            function deg2rad(deg) { return deg * (Math.PI / 180); }
            function handleLocationUpdate(position) {
                const currentLat = position.coords.latitude; const currentLon = position.coords.longitude; const accuracy = position.coords.accuracy; const currentTime = position.timestamp;
                let statusEl = $('#gpsStatus'); statusEl.removeClass('bg-warning bg-danger text-warning text-danger').addClass('bg-success bg-opacity-10 text-success'); statusEl.text(`Tracking Active (Accuracy: ${Math.round(accuracy)}m)`);
                if (accuracy > 50) { statusEl.removeClass('bg-success text-success').addClass('bg-warning bg-opacity-10 text-warning'); statusEl.text(`Calibrating... (${Math.round(accuracy)}m)`); return; }
                if (lastPosition !== null) {
                    let distanceDelta = getDistanceFromLatLonInKm(lastPosition.lat, lastPosition.lon, currentLat, currentLon);
                    let timeDeltaHours = (currentTime - lastTime) / (1000 * 60 * 60);
                    if (timeDeltaHours > 0) {
                        let speedKmh = distanceDelta / timeDeltaHours;
                        if (speedKmh > MAX_SPEED_KMH) { statusEl.removeClass('bg-success text-success').addClass('bg-danger bg-opacity-10 text-danger'); statusEl.text("Speed warning. Point ignored."); return; }
                    }
                    totalDistanceKm += distanceDelta; $('#distanceLabel').text(totalDistanceKm.toFixed(2));
                }
                lastPosition = { lat: currentLat, lon: currentLon }; lastTime = currentTime;
            }
            function locationError(err) { $('#gpsStatus').removeClass('bg-warning bg-success text-warning text-success').addClass('bg-danger bg-opacity-10 text-danger').text(`GPS Error: ${err.message}`); }

            $('#startJogging').click(function () {
                if (!navigator.geolocation) { alert("Geolocation is not supported by your browser."); return; }
                $(this).addClass('d-none'); $('#stopJogging').removeClass('d-none');
                seconds = 0; totalDistanceKm = 0; lastPosition = null; $('#stopwatch').text("00:00:00"); $('#distanceLabel').text("0.00");
                $('#gpsStatus').removeClass('bg-danger bg-success text-danger text-success').addClass('bg-warning bg-opacity-10 text-warning').text("Acquiring Satellite...");
                timer = setInterval(function () { seconds++; $('#stopwatch').text(formatTime(seconds)); }, 1000);
                watchId = navigator.geolocation.watchPosition(handleLocationUpdate, locationError, { enableHighAccuracy: true, maximumAge: 0, timeout: 10000 });
            });

            $('#stopJogging').click(function () {
                let btn = $(this);
                btn.html('<span class="spinner-border spinner-border-sm"></span> Saving...').prop('disabled', true);
                clearInterval(timer); if (watchId !== null) navigator.geolocation.clearWatch(watchId);

                // AJAX Submit Jogging Data
                $.post("{{ route('task.idp.jogging.store') }}", {
                    distance_km: totalDistanceKm.toFixed(2),
                    duration_seconds: seconds
                }).done(function (res) {
                    $('#gpsStatus').removeClass('bg-success bg-warning text-success text-warning').addClass('bg-secondary bg-opacity-10 text-secondary').text("Run Finished & Saved.");
                    alert(res.message);
                }).fail(function (err) {
                    alert('Gagal menyimpan aktivitas lari. Silakan coba lagi.');
                }).always(function () {
                    btn.addClass('d-none').prop('disabled', false).html('<span class="fs-5 me-2">⏹️</span> Stop & Save');
                    $('#startJogging').removeClass('d-none');
                });
            });


            // ==========================================
            // 2. LAPORAN SHALAT & VALIDASI WAKTU
            // ==========================================
            // Ambil data konfigurasi waktu dari database yang dikirim lewat Controller
            const prayerSchedules = @json($prayerSchedules);
            const todaySubmissions = @json($todaySubmissions);

            function fetchServerTime(callback) {
                $.get("{{ route('api.server-time') }}", function (data) {
                    let serverNow = new Date(data.server_time);
                    callback(serverNow);
                }).fail(function () {
                    $('#timeStatusMsg').html('<span class="text-danger">Gagal mengambil waktu server.</span>');
                });
            }

            // Fungsi bantu ubah string jam (04:30:00) jadi objek Date di hari ini
            function parseTimeToDate(timeStr, baseDate) {
                let [hours, minutes, seconds] = timeStr.split(':');
                let d = new Date(baseDate.getTime());
                d.setHours(hours, minutes, seconds || 0, 0);
                return d;
            }

            let clockInterval;
            function startTimeSync() {
                clearInterval(clockInterval);
                $('#timeStatusMsg').html('<span class="spinner-border spinner-border-sm me-1"></span> Memeriksa Waktu...');
                $('#submitPrayerBtn').prop('disabled', true);

                fetchServerTime(function (serverTime) {
                    clockInterval = setInterval(() => {
                        let deviceTime = new Date();
                        serverTime.setSeconds(serverTime.getSeconds() + 1); // Tambah 1 detik real-time

                        const fmtDevice = deviceTime.toTimeString().split(' ')[0];
                        const fmtServer = serverTime.toTimeString().split(' ')[0];

                        $('#deviceTimeDisplay').text(fmtDevice);
                        $('#serverTimeDisplay').text(fmtServer);

                        // 1. Dapatkan tipe shalat yang dipilih
                        let selectedType = $('input[name="prayerType"]:checked').val();
                        let schedule = prayerSchedules[selectedType];

                        // Perbarui UI rentang jam
                        if (schedule) {
                            let startFmt = schedule.start_time.substring(0, 5);
                            let endFmt = schedule.end_time.substring(0, 5);
                            $('#scheduleWindowDisplay').text(`${startFmt} - ${endFmt}`);
                        } else {
                            $('#scheduleWindowDisplay').text('Jadwal belum diatur');
                        }

                        // 2. Cek apakah sudah submit hari ini
                        let isAlreadySubmitted = todaySubmissions.includes(selectedType);

                        // Cek kondisi validasi untuk merubah UI
                        let timeDiffSeconds = Math.abs(deviceTime - serverTime) / 1000;
                        let statusBox = $('#timeValidationBox');
                        let statusMsg = $('#timeStatusMsg');
                        let submitBtn = $('#submitPrayerBtn');

                        // Reset class
                        statusBox.removeClass('border-success border-danger border-warning');
                        statusMsg.removeClass('text-success text-danger text-warning');

                        if (isAlreadySubmitted) {
                            statusBox.addClass('border-success');
                            statusMsg.addClass('text-success').text(`✅ Anda sudah melaporkan ${selectedType} hari ini.`);
                            submitBtn.prop('disabled', true);
                        }
                        else if (!schedule) {
                            statusBox.addClass('border-danger');
                            statusMsg.addClass('text-danger').text('❌ Jadwal dari database tidak ditemukan.');
                            submitBtn.prop('disabled', true);
                        }
                        else {
                            // Konversi jam database ke objek Date
                            let windowStart = parseTimeToDate(schedule.start_time, serverTime);
                            let windowEnd = parseTimeToDate(schedule.end_time, serverTime);

                            if (serverTime < windowStart) {
                                statusBox.addClass('border-warning');
                                statusMsg.addClass('text-warning').text(`⚠️ Waktu Lapor ${selectedType} belum dimulai.`);
                                submitBtn.prop('disabled', true);
                            }
                            else if (serverTime > windowEnd) {
                                statusBox.addClass('border-danger');
                                statusMsg.addClass('text-danger').text(`❌ Waktu Lapor ${selectedType} sudah berakhir.`);
                                submitBtn.prop('disabled', true);
                            }
                            else if (timeDiffSeconds > 60) { // Cek manipulasi jam HP user
                                statusBox.addClass('border-danger');
                                statusMsg.addClass('text-danger').text('❌ Waktu perangkat Anda dimanipulasi/tidak akurat!');
                                submitBtn.prop('disabled', true);
                            }
                            else {
                                statusBox.addClass('border-success');
                                statusMsg.addClass('text-success').text(`✅ Waktu Valid. Silakan submit ${selectedType}.`);
                                submitBtn.prop('disabled', false);
                            }
                        }

                    }, 1000);
                });
            }
            $('#prayer-tab').on('shown.bs.tab', function (e) { startTimeSync(); });
            $('input[name="prayerType"]').change(function () { startTimeSync(); });
            $('#prayer-tab').on('hidden.bs.tab', function (e) { clearInterval(clockInterval); });

            // ---> TAMBAHKAN KODE AJAX INI DI SINI <---

            // AJAX Submit Laporan Shalat
            $('#prayerForm').submit(function (e) {
                e.preventDefault();
                let btn = $('#submitPrayerBtn');
                let originalText = btn.html();

                // Ubah state tombol saat proses loading
                btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...').prop('disabled', true);

                let selectedType = $('input[name="prayerType"]:checked').val();

                // Format waktu device saat tombol submit ditekan untuk dikirim ke backend 
                // .toISOString() menghasilkan format standar yang bisa di-parse oleh Carbon
                let currentDeviceTime = new Date().toISOString();

                $.post("/task/idp/prayer", {
                    // Gunakan _token jika CSRF token tidak di-setup secara global di $.ajaxSetup
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    prayer_type: selectedType,
                    device_time: currentDeviceTime
                }).done(function (res) {
                    // Tampilkan pesan sukses dari server
                    alert(res.message);

                    // Reload halaman agar daftar "Riwayat Laporan" dan variabel todaySubmissions ter-update
                    location.reload();
                }).fail(function (err) {
                    // Tampilkan pesan error dari throw 422 di controller
                    let errorMsg = "Gagal mengirim laporan shalat.";
                    if (err.responseJSON && err.responseJSON.message) {
                        errorMsg = err.responseJSON.message;
                    }
                    alert(errorMsg);

                    // Kembalikan tombol ke kondisi semula jika gagal
                    btn.html(originalText).prop('disabled', false);
                });
            });

            // ==========================================
            // 3. BOOK REPORT / READING
            // ==========================================

            // AJAX Submit Proposal Buku
            $('#bookProposalForm').submit(function (e) {
                e.preventDefault();
                let btn = $('#submitProposalBtn');
                btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Sending...').prop('disabled', true);

                $.post("{{ route('task.idp.book-proposal.store') }}", {
                    title: $('#bookTitle').val(),
                    author: $('#authorName').val()
                }).done(function (res) {
                    alert(res.message);
                    location.reload(); // Reload memunculkan tampilan "Menunggu Persetujuan"
                }).fail(function (err) {
                    alert("Gagal mengirim proposal: " + err.responseJSON.message);
                    btn.html('Request Approval').prop('disabled', false);
                });
            });

            // AJAX Submit Log Harian Membaca Buku
            $('#dailyLogForm').submit(function (e) {
                e.preventDefault();
                let btn = $('#submitLogBtn');
                btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Saving...').prop('disabled', true);

                $.post("{{ route('task.idp.book-log.store') }}", {
                    book_proposal_id: $('#bookProposalId').val(),
                    page_from: $('#pageFrom').val(),
                    page_to: $('#pageTo').val(),
                    summary: $('#summary').val()
                }).done(function (res) {
                    alert(res.message);
                    // Reset isi form
                    $('#pageFrom').val('');
                    $('#pageTo').val('');
                    $('#summary').val('');
                }).fail(function (err) {
                    alert("Gagal menyimpan log: " + err.responseJSON.message);
                }).always(function () {
                    btn.html('Save Progress').prop('disabled', false);
                });
            });

        });
    </script>
@endpush