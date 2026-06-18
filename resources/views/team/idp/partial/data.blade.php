@if($selectedUser)
    <div class="card glass-card mb-4 border-0">
        <div class="card-body p-4 d-flex align-items-center">
            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                style="width: 60px; height: 60px; font-size: 24px; font-weight: bold;">
                {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
            </div>
            <div>
                <h5 class="mb-1 fw-bold">{{ $selectedUser->name }}</h5>
                <p class="mb-0 text-muted small"><i class="bi bi-envelope"></i>
                    {{ $selectedUser->email ?? $selectedUser->username }}</p>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills custom-pills mb-4 d-flex flex-nowrap overflow-auto pb-2" id="activityTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center" id="jogging-tab" data-bs-toggle="pill"
                data-bs-target="#jogging" type="button" role="tab">
                <span class="fs-5 me-2">🏃‍♂️</span> Jogging Track
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center" id="prayer-tab" data-bs-toggle="pill"
                data-bs-target="#prayer" type="button" role="tab">
                <span class="fs-5 me-2">🕌</span> Prayer Reports
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center" id="book-tab" data-bs-toggle="pill" data-bs-target="#book"
                type="button" role="tab">
                <span class="fs-5 me-2">📖</span> Book Reading
            </button>
        </li>
    </ul>

    <div class="tab-content" id="activityTabsContent">

        <div class="tab-pane fade show active" id="jogging" role="tabpanel" tabindex="0">
            <div class="card glass-card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="table-light-custom">
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Jarak (KM)</th>
                                    <th>Durasi (Detik)</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($selectedUser->joggingTracks as $jogging)
                                    <tr>
                                        <td class="ps-4 fw-medium">
                                            {{ \Carbon\Carbon::parse($jogging->created_at)->format('d M Y') }}</td>
                                        <td><span class="badge bg-primary rounded-pill px-3">{{ $jogging->distance_km }}
                                                KM</span></td>
                                        <td>{{ gmdate("H:i:s", $jogging->duration_seconds) }}</td>
                                        <td class="text-muted">{{ \Carbon\Carbon::parse($jogging->start_time)->format('H:i') }}
                                        </td>
                                        <td class="text-muted">{{ \Carbon\Carbon::parse($jogging->end_time)->format('H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada data jogging.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="prayer" role="tabpanel" tabindex="0">
            <div class="card glass-card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="table-light-custom">
                                <tr>
                                    <th class="ps-4">Tanggal Lapor</th>
                                    <th>Jenis Ibadah</th>
                                    <th>Waktu Perangkat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($selectedUser->prayerReports as $prayer)
                                    <tr>
                                        <td class="ps-4 fw-medium">
                                            {{ \Carbon\Carbon::parse($prayer->reported_at)->format('d M Y, H:i') }}</td>
                                        <td><span
                                                class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">{{ $prayer->prayer_type }}</span>
                                        </td>
                                        <td class="text-muted">
                                            {{ \Carbon\Carbon::parse($prayer->device_time)->format('d M Y, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">Belum ada data ibadah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

     <div class="tab-pane fade" id="book" role="tabpanel" tabindex="0">
            <div class="card glass-card border-0">
                <div class="card-body p-0">
                    <div class="accordion accordion-flush custom-accordion" id="accordionBooks">
                        @forelse($selectedUser->bookProposals as $index => $book)
                            <div class="accordion-item bg-transparent">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }} bg-transparent fw-bold"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#book-{{ $book->id }}">
                                        {{ $book->title }} &nbsp; <small class="text-muted fw-normal">- {{ $book->author }}</small>
                                        <span id="badge-book-{{ $book->id }}"
                                            class="badge ms-auto me-3 rounded-pill {{ $book->status == 'approved' ? 'bg-success' : ($book->status == 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ ucfirst($book->status) }}
                                        </span>
                                    </button>
                                </h2>
                                <div id="book-{{ $book->id }}"
                                    class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                    data-bs-parent="#accordionBooks">
                                    <div class="accordion-body border-top border-light-subtle">

                                        <!-- ALERT TENGGAT WAKTU -->
                                        @if($book->status == 'approved' && $book->due_date)
                                            <div class="alert alert-info py-2 small mb-3 border-0 bg-info bg-opacity-10 text-white">
                                                <i class="bi bi-calendar-event me-2 text-white"></i> Tenggat Waktu Membaca:
                                                <strong>{{ \Carbon\Carbon::parse($book->due_date)->format('d M Y') }}</strong>
                                            </div>
                                        @endif

                                        <!-- AKSI APPROVAL (JIKA PENDING) -->
                                        @if($book->status == 'pending')
                                            <div class="action-buttons mb-4 p-3 bg-light rounded shadow-sm" id="action-container-{{ $book->id }}">
                                                <div class="mb-3 border-bottom pb-2">
                                                    <h6 class="fw-bold mb-0">Tindakan Atasan</h6>
                                                    <small class="text-muted">Pilih tenggat waktu jika Anda menyetujui proposal ini.</small>
                                                </div>

                                                <div class="row align-items-center">
                                                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                                                        <label for="due-date-{{ $book->id }}" class="form-label small fw-bold text-primary">Tenggat Waktu (Due Date) <span class="text-danger">*</span></label>
                                                        <input type="date" id="due-date-{{ $book->id }}" class="form-control form-control-sm border-primary" min="{{ date('Y-m-d') }}">
                                                    </div>

                                                    <div class="col-12 col-md-6 text-md-end align-self-end">
                                                        <button class="btn btn-sm btn-danger rounded-pill px-4 me-2 btn-action-book" data-id="{{ $book->id }}" data-status="rejected">Tolak</button>
                                                        <button class="btn btn-sm btn-success rounded-pill px-4 btn-action-book" data-id="{{ $book->id }}" data-status="approved">Setujui</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- SUMMARY STATISTIK MEMBACA -->
                                        @php
                                            $totalSesi = $book->readingLogs->count();
                                            $halamanTerakhir = $book->readingLogs->max('page_to') ?? 0;
                                        @endphp
                                        
                                        @if($totalSesi > 0)
                                            <div class="d-flex justify-content-around align-items-center mb-4 p-3 bg-primary bg-opacity-10 rounded-3 border border-primary border-opacity-25">
                                                <div class="text-center">
                                                    <span class="d-block text-white small mb-1">Total Sesi Baca</span>
                                                    <span class="fw-bold fs-5 text-white">{{ $totalSesi }} Kali</span>
                                                </div>
                                                <div class="vr text-white opacity-25"></div>
                                                <div class="text-center">
                                                    <span class="d-block text-white small mb-1">Halaman Terakhir</span>
                                                    <span class="fw-bold fs-5 text-white">Hal. {{ $halamanTerakhir }}</span>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- LIST LOG MEMBACA (EXPANDABLE) -->
                                        <h6 class="fw-bold mb-3">Detail Log Membaca:</h6>
                                        <div class="list-group list-group-flush mb-2">
                                            @forelse($book->readingLogs as $log)
                                                <div class="list-group-item bg-transparent px-0 py-3 border-bottom border-light-subtle">
                                                    <!-- Header yang bisa di-klik -->
                                                    <div class="d-flex w-100 justify-content-between align-items-center" 
                                                         data-bs-toggle="collapse" 
                                                         data-bs-target="#log-detail-{{ $log->id }}" 
                                                         style="cursor: pointer;">
                                                        
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-chevron-down text-muted me-3" style="font-size: 0.8rem;"></i>
                                                            <h6 class="mb-0 fw-bold text-dark">
                                                                {{ \Carbon\Carbon::parse($log->log_date)->format('d M Y') }}
                                                            </h6>
                                                        </div>
                                                        <span class="badge bg-primary bg-opacity-10 text-white border border-primary rounded-pill px-3 py-2">
                                                            Hal. {{ $log->page_from }} - {{ $log->page_to }}
                                                        </span>
                                                    </div>

                                                    <!-- Konten Detail/Summary yang tersembunyi -->
                                                    <div class="collapse mt-3" id="log-detail-{{ $log->id }}">
                                                        <div class="card card-body bg-light border-0 rounded-3 text-sm text-muted p-3">
                                                            <h6 class="fw-bold text-dark mb-2" style="font-size: 0.85rem;">Ringkasan / Catatan:</h6>
                                                            <!-- Ganti $log->summary dengan nama kolom summary di database Anda -->
                                                            <p class="mb-0 fst-italic">
                                                                "{{ $log->summary ?? 'Tidak ada ringkasan yang ditulis untuk sesi membaca ini.' }}"
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-4 text-muted fst-italic bg-light rounded-3">
                                                    Belum ada log membaca untuk buku ini.
                                                </div>
                                            @endforelse
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">Belum ada data pengajuan baca buku.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif