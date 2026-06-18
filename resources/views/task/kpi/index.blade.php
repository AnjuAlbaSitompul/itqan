@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5" style="max-width: 1000px;">
    
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="letter-spacing: -0.3px;">Realisasi KPI Mandiri</h4>
            <p class="text-muted small mb-0">Laporkan hasil pengerjaan target kinerja Anda untuk periode aktif.</p>
        </div>
        <div>
                <select name="period_id" class="form-select bg-white border-light shadow-sm select-modern" onchange="this.form.submit();">
                    @foreach($periods as $p)
                        <option value="{{ $p->id }}" {{ $selectedPeriodId == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
        </div>
    </div>

    <div id="alertContainer">
        @if(!$userKpi)
            <div class="alert bg-light border border-dashed rounded-3 p-4 text-center mb-4">
                <i class="fe fe-alert-circle text-secondary fs-3 mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Data Penugasan Tidak Ditemukan</h6>
                <p class="text-muted small mb-0">Anda belum mendaftarkan atau memiliki target KPI pada periode ini.</p>
            </div>
        @elseif(!$isApproved)
            <div class="alert alert-warning border-0 shadow-sm rounded-3 p-3 d-flex align-items-center mb-4">
                <i class="fe fe-clock fs-4 me-3 text-warning"></i>
                <div>
                    <strong class="d-block text-dark">Formulir Terkunci (Menunggu Approval)</strong>
                    <span class="small text-secondary">KPI Anda berstatus <b>{{ strtoupper($userKpi->kpiApproval->status) }}</b>. Pengisian dibuka setelah disetujui Atasan.</span>
                </div>
            </div>
        @elseif($isExpired)
            <div class="alert alert-danger border-0 shadow-sm rounded-3 p-3 d-flex align-items-center mb-4">
                <i class="fe fe-lock fs-4 me-3 text-danger"></i>
                <div>
                    <strong class="d-block text-dark">Masa Pengisian Telah Berakhir</strong>
                    <span class="small text-secondary">Batas waktu pelaporan periode ini telah habis pada <b>{{ \Carbon\Carbon::parse($periodInfo->period_end)->format('d M Y') }}</b>.</span>
                </div>
            </div>
        @else
            <div class="alert alert-success border-0 shadow-sm rounded-3 p-3 d-flex align-items-center mb-4">
                <i class="fe fe-check-circle fs-4 me-3 text-success"></i>
                <div>
                    <strong class="d-block text-dark">KPI Disetujui & Siap Diisi</strong>
                    <span class="small text-secondary">Silakan perbarui capaian Anda sebelum batas akhir pada <b>{{ \Carbon\Carbon::parse($periodInfo->period_end)->format('d M Y') }}</b>.</span>
                </div>
            </div>
        @endif
    </div>

    @if($userKpi && $isApproved)
        <form id="kpiRealizationForm">
            @csrf
            <input type="hidden" name="user_kpi_id" value="{{ $userKpi->id }}">

            <div class="row">
                @foreach($userKpi->kpiApproval->kpiDetails as $index => $detail)
                    @php
                        $existingReal = $userKpi->realizations->where('user_kpi_detail_id', $detail->id)->first();
                        $target = floatval($detail->masterKpi?->target ?? 1);
                        $bobot = floatval($detail->masterKpi?->bobot ?? 0);
                    @endphp
                    
                    <div class="col-12 mb-4">
                        <div class="card kpi-card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
                            <div class="card-header bg-white border-bottom p-4">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold text-dark mb-2">{{ $detail->masterKpi?->title }}</h5>
                                        
                                        <div class="row g-2 text-muted small mb-3">
                                            <div class="col-12">
                                                <i class="fe fe-check-square me-1"></i> <strong>DoD:</strong> {{ $detail->masterKpi?->definition_of_done ?? '-' }}
                                            </div>
                                            @if($detail->masterKpi?->guard_rail)
                                                <div class="col-12 text-danger mt-1">
                                                    <i class="fe fe-shield me-1"></i> <strong>Guard Rail:</strong> {{ $detail->masterKpi->guard_rail }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                                            <span class="badge bg-light text-secondary border fw-medium" style="font-size: 0.7rem;">Formula Acuan:</span>
                                            @forelse($detail->masterKpi?->formulas ?? [] as $formula)
                                                <span class="badge bg-light text-dark border border-success-subtle px-2 py-1 shadow-sm" style="font-size: 0.75rem;">
                                                    {{ floatval($formula->from) }} <span class="text-muted mx-1">s/d</span> {{ floatval($formula->to)  }} <span class="text-success fw-semibold">({{ floatval($formula->progress) }}%)</span>
                                                </span>
                                            @empty
                                                <span class="text-muted small fst-italic">Belum ada formula acuan</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row flex-md-column align-items-end gap-2 text-end" style="min-width: 150px;">
                                        <div class="bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-3 text-center w-100 border border-primary-subtle">
                                            <small class="d-block text-uppercase fw-semibold text-white" style="font-size: 0.7rem;">Target</small>
                                            <span class="fs-5 fw-bold text-white">{{ $target }}</span> <span class="small text-white">{{ $detail->masterKpi?->satuan }}</span>
                                        </div>
                                        <div class="bg-light text-dark px-3 py-2 rounded-3 text-center w-100 border">
                                            <small class="d-block text-uppercase text-muted fw-semibold text-primary" style="font-size: 0.65rem;">Bobot</small>
                                            <span class="fs-6 fw-bold text-primary">{{ $bobot }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body bg-light bg-opacity-30 p-4">
                                <div class="row align-items-center g-4">
                                    
                                    <div class="col-md-7 border-end border-light-subtle">
                                        <input type="hidden" name="realizations[{{ $index }}][user_kpi_detail_id]" value="{{ $detail->id }}">
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-dark small mb-1">Nilai Realisasi</label>
                                            <div class="input-group group-clean">
                                                {{-- Menambahkan data-formulas ke dalam input --}}
                                                <input type="number" 
                                                       step="0.01" 
                                                       name="realizations[{{ $index }}][value]" 
                                                       class="form-control form-control-lg input-modern calc-trigger" 
                                                       value="{{ $existingReal?->realization }}"
                                                       placeholder="0.00"
                                                       data-target="{{ $target }}"
                                                       data-bobot="{{ $bobot }}"
                                                       data-formulas="{{ json_encode($detail->masterKpi?->formulas ?? []) }}"
                                                       required
                                                       {{ $isExpired ? 'disabled' : '' }}>
                                                <span class="input-group-text bg-white text-muted border-start-0">{{ $detail->masterKpi?->satuan }}</span>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="form-label fw-semibold text-dark small mb-1">Catatan Pelaporan</label>
                                            <div class="input-group group-clean">
                                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fe fe-message-square"></i></span>
                                                <input type="text" 
                                                       name="realizations[{{ $index }}][notes]" 
                                                       class="form-control input-modern border-start-0 ps-0" 
                                                       value="{{ $existingReal?->notes }}"
                                                       placeholder="Keterangan pendukung atau lampiran link bukti..."
                                                       {{ $isExpired ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="row text-center g-3">
                                            <div class="col-6">
                                                <div class="p-3 bg-white rounded-3 shadow-sm border border-light">
                                                    <small class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.65rem;">Pencapaian</small>
                                                    <h3 class="mb-0 fw-bold achievement-text text-secondary">0.00%</h3>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 bg-white rounded-3 shadow-sm border border-light">
                                                    <small class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.65rem;">Estimasi Nilai</small>
                                                    <h3 class="mb-0 fw-bold score-text text-secondary">0.00</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card shadow border-0 rounded-4 sticky-bottom mb-4 text-white p-4" style="bottom: 20px; z-index: 100;">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-4">
                        <div class="border-end border-secondary pe-4">
                            <small class="text-secondary d-block text-uppercase fw-semibold tracking-wider mb-1" style="font-size: 0.65rem;">Rata-rata Capaian</small>
                            <h3 class="mb-0 fw-bold text-success" id="global-avg-achievement">0.00%</h3>
                        </div>
                        <div>
                            <small class="text-secondary d-block text-uppercase fw-semibold tracking-wider mb-1" style="font-size: 0.65rem;">Total Nilai Skor Akhir</small>
                            <h2 class="mb-0 fw-bold text-primary" id="global-total-score">0.00</h2>
                        </div>
                    </div>
                    @if(!$isExpired)
                        <button type="submit" id="btnSubmitRealization" class="btn btn-primary btn-lg px-5 shadow fw-bold d-flex align-items-center gap-2 rounded-3">
                            <i class="fe fe-save"></i> <span>Simpan Seluruh Realisasi</span>
                        </button>
                    @endif
                </div>
            </div>
        </form>
    @endif
</div>
@endsection

@push('styles')
<style>
    .select-modern { min-width: 220px; border-radius: 8px; font-size: 0.9rem; border-color: #e2e8f0; }
    .select-modern:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    
    .input-modern { border-color: #e2e8f0; background-color: #fff; box-shadow: none; transition: 0.2s; font-size: 0.95rem; }
    .input-modern:focus { border-color: #3b82f6; background-color: #fff; }
    .group-clean .input-modern:focus + .input-group-text,
    .group-clean .input-group-text:has(+ .input-modern:focus) { border-color: #3b82f6; }
    .input-group-text { border-color: #e2e8f0; }

    .kpi-card { transition: transform 0.2s, box-shadow 0.2s; border: 1px solid #f1f5f9 !important; }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 12px 20px -5px rgba(0,0,0,0.05)!important; }

    .toast-modern {
        position: fixed; top: 20px; right: 20px; z-index: 9999;
        background: #1e293b; color: #fff;
        padding: 14px 28px; border-radius: 10px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        display: none; font-size: 0.95rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof jQuery === 'undefined') return;

        if ($('#toastApp').length === 0) {
            $('body').append('<div id="toastApp" class="toast-modern fw-medium"></div>');
        }
        
        function showToast(message, isSuccess = true) {
            let toast = $('#toastApp');
            toast.html(`<i class="fe ${isSuccess ? 'fe-check-circle text-success' : 'fe-x-circle text-danger'} me-2"></i> ${message}`);
            toast.css('border-left', `4px solid ${isSuccess ? '#10b981' : '#ef4444'}`);
            toast.fadeIn('fast').delay(3000).fadeOut('slow');
        }

        // Fungsi Kalkulasi Nilai Item & Akumulasi Global di Bawah
        function calculateKpiValues() {
            let totalScore = 0;
            let sumAchievement = 0;
            let countItems = 0;

            $('.calc-trigger').each(function() {
                let input = $(this);
                let card = input.closest('.kpi-card');
                
                let val = parseFloat(input.val()) || 0;
                let target = parseFloat(input.data('target')) || 1; 
                let bobot = parseFloat(input.data('bobot')) || 0;
                
                // Ambil data formulas dari atribut data
                let rawFormulas = input.attr('data-formulas');
                let formulas = [];
                try {
                    if(rawFormulas) {
                        formulas = JSON.parse(rawFormulas);
                    }
                } catch(e) {
                    console.error("Gagal parsing formula:", e);
                }

                let achievement = 0;

                // Hitung Achievement berdasarkan Range Formula
                if (formulas && formulas.length > 0) {
                    let matchedFormula = formulas.find(f => val >= parseFloat(f.from) && val <= parseFloat(f.to));
                    
                    if (matchedFormula) {
                        achievement = parseFloat(matchedFormula.progress) || 0;
                    } else {
                        // Jika di luar semua range formula, set ke 0
                        achievement = 0;
                    }
                } else {
                    // Fallback calculation jika KPI tidak memiliki spesifik formula
                    achievement = target > 0 ? (val / target) * 100 : 0;
                }

                // Perhitungan Skor Item: Progress * Bobot
                let score = (achievement / 100) * bobot;

                // Akumulasi data untuk panel global di paling bawah
                totalScore += score;
                sumAchievement += achievement;
                countItems++;

                let achElement = card.find('.achievement-text');
                let scoreElement = card.find('.score-text');

                achElement.text(achievement.toFixed(2) + '%');
                scoreElement.text(score.toFixed(2));

                achElement.removeClass('text-success text-warning text-danger text-secondary');
                if(achievement >= 100) achElement.addClass('text-success');
                else if(achievement >= 50) achElement.addClass('text-warning');
                else if(achievement > 0) achElement.addClass('text-danger');
                else achElement.addClass('text-secondary');
            });

            // Update Tampilan Bar Hitam (Global Accumulation) di paling bawah
            let avgAchievement = countItems > 0 ? (sumAchievement / countItems) : 0;
            $('#global-avg-achievement').text(avgAchievement.toFixed(2) + '%');
            $('#global-total-score').text(totalScore.toFixed(2));
        }

        calculateKpiValues();

        $(document).on('input keyup change', '.calc-trigger', function() {
            calculateKpiValues();
        });

        // AJAX submit handler prevent reloading
        $(document).on('submit', '#kpiRealizationForm', function (e) {
            e.preventDefault();

            let form = $(this);
            let btn = $('#btnSubmitRealization');
            let btnText = btn.find('span');
            let btnIcon = btn.find('i');

            btn.prop('disabled', true);
            btnText.text('Menyimpan Data...');
            btnIcon.attr('class', 'fe fe-loader spinner-border spinner-border-sm border-0 m-0');

            $.ajax({
                url: '{{ route('kpi.realization.store') }}',
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        showToast(response.message, true);
                    } else {
                        showToast(response.message || 'Terjadi kesalahan sistem.', false);
                    }
                },
                error: function (xhr) {
                    let errorMsg = 'Gagal menyimpan data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showToast(errorMsg, false);
                },
                complete: function () {
                    btn.prop('disabled', false);
                    btnText.text('Simpan Seluruh Realisasi');
                    btnIcon.attr('class', 'fe fe-save');
                }
            });
        });
    });
</script>
@endpush