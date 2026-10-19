@extends('layouts.app')

@section('title', 'Buku Tamu dan Presensi Kunjungan | CAP 2026')

@section('content')
<div class="container container-narrow">
    <div class="form-page-header">
        <a href="{{ route('screen.index') }}" class="btn btn-outline btn-sm" style="margin-bottom: 16px;">
            Kembali ke Layar Utama
        </a>
        <h2 style="font-size: 1.8rem; font-weight: 800; color: var(--slate-dark); margin-bottom: 6px;">
            Buku Tamu dan Presensi Kunjungan
        </h2>
        <p style="font-size: 0.95rem; color: var(--text-muted);">
            Collaborative Action Program (CAP) 2026 • Posko Pengelolaan Sampah dan Informasi
        </p>
    </div>

    <div class="form-container">
        @if(session('success') && $lastVisit)
            <!-- DIGITAL VISITOR PASS / BUKTI KEHADIRAN -->
            <div class="ticket-card" id="ticketCard">
                <div class="ticket-header">
                    <div>
                        <span class="status-pill" style="font-size: 0.75rem;">Presensi Berhasil Dicatat</span>
                        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-dark); margin-top: 6px;">
                            Tiket Kunjungan
                        </h3>
                    </div>
                    <div class="ticket-code">{{ $lastVisit->ticket_code }}</div>
                </div>

                <div class="ticket-body">
                    <div class="ticket-row">
                        <label>Nama Pengunjung</label>
                        <value>{{ $lastVisit->is_anonymous ? 'Tamu Anonim' : $lastVisit->nama }}</value>
                    </div>

                    <div class="ticket-row">
                        <label>Jenis Kunjungan</label>
                        <value>{{ $lastVisit->jenis_kunjungan }}</value>
                    </div>

                    <div class="ticket-row">
                        <label>Waktu Kunjungan</label>
                        <value>{{ $lastVisit->created_at->format('H:i:s, d M Y') }}</value>
                    </div>

                    <div class="ticket-row">
                        <label>Status</label>
                        <value style="color: var(--emerald);">Terverifikasi</value>
                    </div>

                    @if($lastVisit->no_telpon)
                        <div class="ticket-row">
                            <label>Nomor Telepon</label>
                            <value>{{ $lastVisit->no_telpon }}</value>
                        </div>
                    @endif

                    @if($lastVisit->instansi)
                        <div class="ticket-row">
                            <label>Instansi atau Asal</label>
                            <value>{{ $lastVisit->instansi }}</value>
                        </div>
                    @endif
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('playground.index') }}" class="btn btn-primary" style="flex: 1;">
                        Lanjut ke Playground dan Tanya Jawab
                    </a>
                    <a href="{{ route('kunjungan.index') }}" class="btn btn-outline">
                        Presensi Tamu Baru
                    </a>
                </div>
            </div>
        @endif

        <div class="card" style="padding: 28px;">
            <!-- ONE-TAP QUICK CHECKIN BUTTON -->
            <div class="quick-checkin-banner">
                <h4 style="font-size: 1rem; font-weight: 700; color: var(--slate-dark); margin-bottom: 4px;">
                    Catat Kehadiran Instan
                </h4>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 12px;">
                    Kehadiran Anda akan dicatat sebagai <strong>Tamu Anonim</strong> tanpa perlu mengisi formulir data pribadi.
                </p>
                <form action="{{ route('kunjungan.quick') }}" method="GET">
                    <button type="submit" class="quick-btn">
                        Catat Cepat sebagai Tamu Anonim
                    </button>
                </form>
            </div>

            <div class="divider-text">
                <span>Atau Masukkan Rincian Kunjungan</span>
            </div>

            <!-- FORM DEFAULT KUNJUNGAN -->
            <form action="{{ route('kunjungan.store') }}" method="POST" id="kunjunganForm">
                @csrf

                <!-- Nama -->
                <div class="form-group">
                    <label class="form-label" for="namaInput">
                        Nama Lengkap <span class="opt">(Kosongkan jika ingin tercatat sebagai Tamu Anonim)</span>
                    </label>
                    <input type="text" name="nama" id="namaInput" class="form-input" placeholder="Masukkan nama Anda (atau biarkan kosong)">
                </div>

                <!-- Nomor Telepon -->
                <div class="form-group">
                    <label class="form-label" for="phoneInput">
                        Nomor Kontak atau WhatsApp <span class="opt">(Opsional)</span>
                    </label>
                    <input type="tel" name="no_telpon" id="phoneInput" class="form-input" placeholder="Contoh: 081234567890">
                </div>

                <!-- Jenis Kunjungan -->
                <div class="form-group">
                    <label class="form-label">
                        Jenis Kunjungan atau Keperluan <span class="req">*</span>
                    </label>
                    <div class="pills-select">
                        <div class="pill-option">
                            <input type="radio" name="jenis_kunjungan" id="jenis1" value="Konsultasi Program CAP" checked>
                            <label for="jenis1" class="pill-label">
                                Konsultasi Program CAP
                            </label>
                        </div>

                        <div class="pill-option">
                            <input type="radio" name="jenis_kunjungan" id="jenis2" value="Kunjungan Lapangan dan Edukasi">
                            <label for="jenis2" class="pill-label">
                                Kunjungan Lapangan
                            </label>
                        </div>

                        <div class="pill-option">
                            <input type="radio" name="jenis_kunjungan" id="jenis3" value="Kemitraan dan Riset">
                            <label for="jenis3" class="pill-label">
                                Kemitraan dan Riset
                            </label>
                        </div>

                        <div class="pill-option">
                            <input type="radio" name="jenis_kunjungan" id="jenis4" value="Koordinasi Pengelola Bale Resik">
                            <label for="jenis4" class="pill-label">
                                Pengelola Bale Resik
                            </label>
                        </div>

                        <div class="pill-option">
                            <input type="radio" name="jenis_kunjungan" id="jenis5" value="Kunjungan Umum">
                            <label for="jenis5" class="pill-label">
                                Kunjungan Umum
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Instansi / Asal -->
                <div class="form-group">
                    <label class="form-label" for="instansiInput">
                        Asal Instansi atau Lembaga <span class="opt">(Opsional)</span>
                    </label>
                    <input type="text" name="instansi" id="instansiInput" class="form-input" placeholder="Contoh: Dinas Lingkungan Hidup, Universitas, Warga">
                </div>

                <!-- Keperluan / Catatan -->
                <div class="form-group">
                    <label class="form-label" for="keperluanInput">
                        Keterangan atau Keperluan Kunjungan <span class="opt">(Opsional)</span>
                    </label>
                    <textarea name="keperluan" id="keperluanInput" class="form-textarea" rows="2" placeholder="Tuliskan keterangan singkat maksud kunjungan Anda..."></textarea>
                </div>

                <!-- Opsi Anonim Checkbox -->
                <div class="form-group" style="margin-top: 10px;">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_anonymous" id="isAnonymousCheckbox" value="1">
                        <span>Tandai kehadiran saya sebagai <strong>Tamu Anonim</strong></span>
                    </label>
                </div>

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn btn-emerald btn-block" style="padding: 13px; font-size: 0.95rem;">
                        Kirim dan Catat Kehadiran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
