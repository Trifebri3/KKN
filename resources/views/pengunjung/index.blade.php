@extends('layouts.app')

@section('title', 'Daftar Pengunjung dan Statistik Kehadiran | CAP 2026')

@section('content')
<div class="container" style="padding-top: 24px; padding-bottom: 60px;">
    <!-- PAGE HEADER -->
    <div class="page-top-header">
        <div>
            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 8px;">
                <a href="{{ route('screen.index') }}" class="btn btn-outline btn-sm">
                    Kembali ke Layar Utama
                </a>
                <span class="status-pill" style="font-size: 0.75rem;">Rekapitulasi Presensi</span>
            </div>
            <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--slate-dark); letter-spacing: -0.02em;">
                Daftar Pengunjung dan Statistik Kehadiran
            </h2>
            <p style="font-size: 0.92rem; color: var(--text-muted); margin-top: 4px;">
                Collaborative Action Program (CAP) 2026 • Monitoring Aktivitas Tamu, Konsultasi, dan Kunjungan Lapangan
            </p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('pengunjung.cetak', request()->query()) }}" target="_blank" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                <span>Cetak / Unduh PDF</span>
            </a>
            <a href="{{ route('kunjungan.index', ['baru' => 1]) }}" class="btn btn-outline">
                Presensi Tamu Baru
            </a>
        </div>
    </div>

    <!-- STATS SUMMARY CARDS -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-value">{{ number_format($totalVisits, 0, ',', '.') }}</div>
            <div class="stat-label">Total Seluruh Kunjungan</div>
            <div class="stat-subtext">Akumulasi sejak awal program</div>
        </div>

        <div class="stat-card">
            <div class="stat-value" style="color: var(--primary);">{{ number_format($todayVisits, 0, ',', '.') }}</div>
            <div class="stat-label">Kunjungan Hari Ini</div>
            <div class="stat-subtext">Aktivitas presensi hari ini</div>
        </div>

        <div class="stat-card">
            <div class="stat-value" style="color: var(--emerald);">{{ number_format($identifiedCount, 0, ',', '.') }}</div>
            <div class="stat-label">Tamu Teridentifikasi</div>
            <div class="stat-subtext">Melengkapi nama dan keperluan</div>
        </div>

        <div class="stat-card">
            <div class="stat-value" style="color: var(--slate);">{{ number_format($anonymousCount, 0, ',', '.') }}</div>
            <div class="stat-label">Tamu Anonim</div>
            <div class="stat-subtext">Pencatatan cepat melalui pemindaian</div>
        </div>
    </div>

    <!-- CATEGORY DISTRIBUTION BAR -->
    @if($jenisDistribution->count() > 0)
        <div class="card" style="padding: 24px; margin-bottom: 24px;">
            <div class="section-header" style="margin-bottom: 16px;">
                <h3 class="section-title">Distribusi Kategori Kunjungan</h3>
                <span style="font-size: 0.82rem; color: var(--text-dim);">Berdasarkan data kehadiran masuk</span>
            </div>

            <div class="distribution-grid">
                @foreach($jenisDistribution as $item)
                    @php
                        $percentage = $totalVisits > 0 ? round(($item->total / $totalVisits) * 100, 1) : 0;
                    @endphp
                    <div class="distribution-item">
                        <div class="distribution-info">
                            <span class="distribution-name">{{ $item->jenis_kunjungan }}</span>
                            <span class="distribution-meta">{{ $item->total }} kunjungan ({{ $percentage }}%)</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width: {{ $percentage }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- FILTER & SEARCH CONTROLS -->
    <div class="card" style="padding: 20px; margin-bottom: 24px;">
        <form action="{{ route('pengunjung.index') }}" method="GET" class="filter-form">
            <div class="filter-group">
                <label class="filter-label" for="periodeSelect">Rentang Waktu:</label>
                <select name="periode" id="periodeSelect" class="filter-select">
                    <option value="semua" {{ request('periode') === 'semua' ? 'selected' : '' }}>Semua Waktu</option>
                    <option value="hari_ini" {{ request('periode') === 'hari_ini' ? 'selected' : '' }}>Hari Ini Saja</option>
                    <option value="7_hari" {{ request('periode') === '7_hari' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="30_hari" {{ request('periode') === '30_hari' ? 'selected' : '' }}>30 Hari Terakhir</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="statusSelect">Status Identitas:</label>
                <select name="status" id="statusSelect" class="filter-select">
                    <option value="semua" {{ request('status') === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="terdaftar" {{ request('status') === 'terdaftar' ? 'selected' : '' }}>Tamu Terdaftar</option>
                    <option value="anonim" {{ request('status') === 'anonim' ? 'selected' : '' }}>Tamu Anonim</option>
                </select>
            </div>

            <div class="filter-group" style="flex: 1; min-width: 220px;">
                <label class="filter-label" for="cariInput">Pencarian Kata Kunci:</label>
                <input type="text" name="cari" id="cariInput" class="filter-input" placeholder="Cari nama, nomor tiket, instansi..." value="{{ request('cari') }}">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary btn-sm" style="padding: 9px 18px;">
                    Terapkan Filter
                </button>
                @if(request()->hasAny(['periode', 'status', 'cari']))
                    <a href="{{ route('pengunjung.index') }}" class="btn btn-outline btn-sm" style="padding: 9px 14px;">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- VISITORS TABLE CARD -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--slate-dark);">
                    Tabel Rekapitulasi Kehadiran
                </h3>
                <p style="font-size: 0.82rem; color: var(--text-dim); margin-top: 2px;">
                    Menampilkan {{ $kunjungans->firstItem() ?? 0 }} - {{ $kunjungans->lastItem() ?? 0 }} dari {{ $kunjungans->total() }} data kehadiran
                </p>
            </div>

            <a href="{{ route('pengunjung.cetak', request()->query()) }}" target="_blank" class="btn btn-outline btn-sm">
                Versi Cetak Laporan
            </a>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 130px;">Nomor Tiket</th>
                        <th style="width: 170px;">Waktu Kehadiran</th>
                        <th>Nama Pengunjung</th>
                        <th style="width: 140px;">Kontak</th>
                        <th>Jenis Kunjungan</th>
                        <th>Instansi / Lembaga</th>
                        <th>Keterangan / Keperluan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kunjungans as $visit)
                        <tr>
                            <td>
                                <span class="badge-ticket">{{ $visit->ticket_code }}</span>
                            </td>
                            <td>
                                <div style="font-weight: 600; font-size: 0.86rem; color: var(--slate-dark);">
                                    {{ $visit->created_at->format('H:i') }} WIB
                                </div>
                                <div style="font-size: 0.78rem; color: var(--text-dim);">
                                    {{ $visit->created_at->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div class="avatar-mini {{ $visit->is_anonymous ? 'avatar-anon' : '' }}">
                                        {{ $visit->is_anonymous ? 'A' : strtoupper(substr($visit->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.9rem; color: var(--slate-dark);">
                                            {{ $visit->is_anonymous ? 'Tamu Anonim' : $visit->nama }}
                                        </div>
                                        <span class="badge-status {{ $visit->is_anonymous ? 'badge-anon' : 'badge-named' }}">
                                            {{ $visit->is_anonymous ? 'Anonim' : 'Terdaftar' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.86rem; color: var(--text-muted);">
                                    {{ $visit->no_telpon ?: '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-category">
                                    {{ $visit->jenis_kunjungan }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.86rem; color: var(--text-main);">
                                    {{ $visit->instansi ?: '-' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: var(--text-muted); display: block; max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $visit->keperluan }}">
                                    {{ $visit->keperluan ?: '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px 20px; color: var(--text-dim);">
                                <div style="font-size: 1rem; font-weight: 600; color: var(--slate-dark); margin-bottom: 4px;">
                                    Tidak Ada Data Kunjungan
                                </div>
                                <p style="font-size: 0.86rem;">
                                    Belum ada data yang sesuai dengan kriteria penyaringan yang Anda pilih.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kunjungans->hasPages())
            <div class="pagination-wrapper">
                {{ $kunjungans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
