@extends('layouts.app')

@section('title', 'Presensi Kunjungan Tamu | CAP 2026')

@section('content')
<div class="container container-narrow" style="padding-top: 24px; padding-bottom: 60px;">
    <div class="form-page-header">
        <div style="display: flex; gap: 8px; justify-content: center; margin-bottom: 12px; flex-wrap: wrap;">
            <a href="{{ route('screen.index') }}" class="btn btn-outline btn-sm">
                Kembali ke Layar Utama
            </a>
            <a href="{{ route('pengunjung.index') }}" class="btn btn-outline btn-sm">
                Lihat Daftar Pengunjung
            </a>
        </div>
        <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--slate-dark); margin-bottom: 6px;">
            Buku Tamu dan Presensi Kunjungan
        </h2>
        <p style="font-size: 0.92rem; color: var(--text-muted);">
            Collaborative Action Program (CAP) 2026 • Posko Pengelolaan Sampah dan Informasi Kawasan
        </p>
    </div>

    <div class="form-container">
        @if(isset($visit) && $visit)
            <!-- ACTIVE DIGITAL VISITOR PASS (AUTO REGISTERED ON SCAN) -->
            <div class="ticket-card" id="ticketCard" style="margin-bottom: 24px;">
                <div class="ticket-header">
                    <div>
                        <span class="status-pill" style="font-size: 0.76rem; background: var(--emerald-subtle); color: var(--emerald); border-color: var(--emerald-border);">
                            <span class="status-dot" style="background: var(--emerald);"></span>
                            {{ session('success') ? 'Data Presensi Berhasil Diperbarui' : 'Kehadiran Anda Telah Dicatat Otomatis' }}
                        </span>
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--slate-dark); margin-top: 8px;">
                            Tiket Kunjungan Digital
                        </h3>
                    </div>
                    <div class="ticket-code">{{ $visit->ticket_code }}</div>
                </div>

                <div class="ticket-body">
                    <div class="ticket-row">
                        <label>Nama Pengunjung</label>
                        <value style="font-weight: 700; color: var(--slate-dark);">
                            {{ $visit->is_anonymous ? 'Tamu Anonim' : $visit->nama }}
                        </value>
                    </div>

                    <div class="ticket-row">
                        <label>Status Kehadiran</label>
                        <value>
                            <span class="badge-status {{ $visit->is_anonymous ? 'badge-anon' : 'badge-named' }}">
                                {{ $visit->is_anonymous ? 'Tamu Anonim (Scan Otomatis)' : 'Tamu Teridentifikasi' }}
                            </span>
                        </value>
                    </div>

                    <div class="ticket-row">
                        <label>Jenis Kunjungan</label>
                        <value>{{ $visit->jenis_kunjungan }}</value>
                    </div>

                    <div class="ticket-row">
                        <label>Waktu Kunjungan</label>
                        <value>{{ $visit->created_at->translatedFormat('H:i:s, d M Y') }} WIB</value>
                    </div>

                    @if($visit->no_telpon)
                        <div class="ticket-row">
                            <label>Nomor Kontak / WhatsApp</label>
                            <value>{{ $visit->no_telpon }}</value>
                        </div>
                    @endif

                    @if($visit->instansi)
                        <div class="ticket-row">
                            <label>Instansi atau Lembaga</label>
                            <value>{{ $visit->instansi }}</value>
                        </div>
                    @endif

                    @if($visit->keperluan && !str_contains($visit->keperluan, 'Pencatatan otomatis'))
                        <div class="ticket-row">
                            <label>Keterangan Keperluan</label>
                            <value>{{ $visit->keperluan }}</value>
                        </div>
                    @endif
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px;">
                    <a href="{{ route('playground.index') }}" class="btn btn-primary" style="flex: 1; min-width: 200px;">
                        Lanjut ke Playground & Tanya Jawab
                    </a>
                    <a href="{{ route('pengunjung.index') }}" class="btn btn-outline" style="flex: 1; min-width: 180px;">
                        Lihat Rekap Pengunjung
                    </a>
                    <a href="{{ route('kunjungan.index', ['baru' => 1]) }}" class="btn btn-outline" title="Buat sesi presensi tamu baru">
                        Presensi Baru
                    </a>
                </div>
            </div>
        @endif

        <!-- OPTIONAL DETAILS FORM (UPDATES THE ACTIVE TICKET) -->
        <div class="card" style="padding: 28px;">
            <div style="margin-bottom: 20px;">
                <span class="status-pill" style="font-size: 0.72rem; margin-bottom: 6px;">Formulir Pelengkap</span>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-dark); margin-bottom: 4px;">
                    Lengkapi Identitas Kunjungan (Opsional)
                </h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">
                    Kehadiran Anda sudah resmi tercatat dalam sistem. Jika Anda ingin mencantumkan nama, nomor kontak, atau keperluan konsultasi program CAP 2026, silakan lengkapi formulir di bawah ini untuk memperbarui tiket kunjungan Anda.
                </p>
            </div>

            <form action="{{ route('kunjungan.store') }}" method="POST" id="kunjunganForm">
                @csrf
                @if(isset($visit) && $visit)
                    <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                @endif

                <!-- Nama -->
                <div class="form-group">
                    <label class="form-label" for="namaInput">
                        Nama Lengkap <span class="opt">(Kosongkan jika ingin tetap tercatat sebagai Tamu Anonim)</span>
                    </label>
                    <input type="text" 
                           name="nama" 
                           id="namaInput" 
                           class="form-input" 
                           placeholder="Contoh: Budi Santoso" 
                           value="{{ (isset($visit) && $visit && !$visit->is_anonymous && $visit->nama !== 'Anonymous') ? $visit->nama : '' }}">
                </div>

                <!-- Nomor Telepon -->
                <div class="form-group">
                    <label class="form-label" for="phoneInput">
                        Nomor Kontak atau WhatsApp <span class="opt">(Opsional untuk tindak lanjut konsultasi)</span>
                    </label>
                    <input type="tel" 
                           name="no_telpon" 
                           id="phoneInput" 
                           class="form-input" 
                           placeholder="Contoh: 081234567890" 
                           value="{{ (isset($visit) && $visit && $visit->no_telpon) ? $visit->no_telpon : '' }}">
                </div>

                <!-- Jenis Kunjungan -->
                <div class="form-group">
                    <label class="form-label">
                        Jenis Kunjungan atau Keperluan <span class="req">*</span>
                    </label>
                    <div class="pills-select">
                        @php
                            $currentJenis = (isset($visit) && $visit && $visit->jenis_kunjungan) ? $visit->jenis_kunjungan : 'Konsultasi Program CAP';
                        @endphp

                        <div class="pill-option">
                            <input type="radio" name="jenis_kunjungan" id="jenis1" value="Konsultasi Program CAP" {{ $currentJenis === 'Konsultasi Program CAP' ? 'checked' : '' }}>
                            <label for="jenis1" class="pill-label">
                                Konsultasi Program CAP
                            </label>
                        </div>

                        <div class="pill-option">
                            <input type="radio" name="jenis_kunjungan" id="jenis2" value="Kunjungan Lapangan dan Edukasi" {{ $currentJenis === 'Kunjungan Lapangan dan Edukasi' ? 'checked' : '' }}>
                            <label for="jenis2" class="pill-label">
                                Kunjungan Lapangan
                            </label>
                        </div>

                        <div class="pill-option">
                            <input type="radio" name="jenis_kunjungan" id="jenis3" value="Kemitraan dan Riset" {{ $currentJenis === 'Kemitraan dan Riset' ? 'checked' : '' }}>
                            <label for="jenis3" class="pill-label">
                                Kemitraan dan Riset
                            </label>
                        </div>

                        <div class="pill-option">
                            <input type="radio" name="jenis_kunjungan" id="jenis4" value="Koordinasi Pengelola Bale Resik" {{ $currentJenis === 'Koordinasi Pengelola Bale Resik' ? 'checked' : '' }}>
                            <label for="jenis4" class="pill-label">
                                Pengelola Bale Resik
                            </label>
                        </div>

                        <div class="pill-option">
                            <input type="radio" name="jenis_kunjungan" id="jenis5" value="Kunjungan Umum" {{ $currentJenis === 'Kunjungan Umum' ? 'checked' : '' }}>
                            <label for="jenis5" class="pill-label">
                                Kunjungan Umum
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Instansi / Asal -->
                <div class="form-group">
                    <label class="form-label" for="instansiInput">
                        Asal Instansi, Lembaga, atau Komunitas <span class="opt">(Opsional)</span>
                    </label>
                    <input type="text" 
                           name="instansi" 
                           id="instansiInput" 
                           class="form-input" 
                           placeholder="Contoh: Dinas Lingkungan Hidup, Universitas, Warga Pesisir" 
                           value="{{ (isset($visit) && $visit && $visit->instansi) ? $visit->instansi : '' }}">
                </div>

                <!-- Keperluan / Catatan -->
                <div class="form-group">
                    <label class="form-label" for="keperluanInput">
                        Keterangan atau Keperluan Kunjungan <span class="opt">(Opsional)</span>
                    </label>
                    <textarea name="keperluan" 
                              id="keperluanInput" 
                              class="form-textarea" 
                              rows="2" 
                              placeholder="Tuliskan keterangan singkat maksud kunjungan atau topik yang ingin dikonsultasikan...">{{ (isset($visit) && $visit && $visit->keperluan && !str_contains($visit->keperluan, 'Pencatatan otomatis')) ? $visit->keperluan : '' }}</textarea>
                </div>

                <!-- Opsi Anonim Checkbox -->
                <div class="form-group" style="margin-top: 10px;">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_anonymous" id="isAnonymousCheckbox" value="1" {{ (isset($visit) && $visit && $visit->is_anonymous) ? 'checked' : '' }}>
                        <span>Simpan kehadiran saya sebagai <strong>Tamu Anonim</strong> (Nama tidak akan ditampilkan di publik)</span>
                    </label>
                </div>

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary btn-block" style="padding: 13px; font-size: 0.95rem;">
                        Perbarui Data Kehadiran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
