@extends('layouts.app')

@section('title', 'Layar Informasi dan Presensi | CAP 2026')

@section('content')
<div class="screen-hero">
    <div class="container">
        <!-- Banner Header -->
        <div class="hero-banner">
            <span class="hero-tag">Layar Utama • Pusat Presensi dan Informasi</span>
            <h2 class="hero-title">Collaborative Action Program (CAP) 2026</h2>
            <p class="hero-subtitle">
                Arahkan kamera ponsel Anda ke salah satu kode di bawah ini untuk mencatat kehadiran kunjungan atau mengakses pusat tanya jawab dan asisten virtual.
            </p>
            <div style="margin-top: 14px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('pengunjung.index') }}" class="btn btn-primary btn-sm">
                    Lihat Data Pengunjung & Statistik
                </a>
                <a href="{{ route('pengunjung.cetak') }}" target="_blank" class="btn btn-outline btn-sm">
                    Cetak Laporan PDF
                </a>
                <button type="button" class="btn btn-outline btn-sm" onclick="toggleFullscreen()">
                    Mode Layar Penuh
                </button>
                <button type="button" class="btn btn-outline btn-sm" onclick="openHostModal()">
                    Pengaturan Alamat Tautan
                </button>
            </div>
        </div>

        <!-- 2 MAIN QR CODES GRID -->
        <div class="qr-grid">
            <!-- QR CODE 1: PRESENSI & KUNJUNGAN -->
            <div class="card qr-card" id="cardKunjungan">
                <span class="qr-badge-num">KODE 01</span>
                <h3 class="qr-title">Presensi Kunjungan Tamu</h3>
                <p class="qr-desc">
                    Mencatat kehadiran kunjungan di lokasi program. Kehadiran dapat langsung dicatat sebagai tamu tanpa nama jika kolom tidak diisi, atau lengkapi nama, nomor kontak, serta jenis keperluan Anda.
                </p>

                <!-- QR Display -->
                <div class="qr-wrapper">
                    <img src="{{ $qrKunjungan }}" alt="QR Presensi Kunjungan" class="qr-img" id="imgQrKunjungan">
                </div>

                <!-- Feature Pills -->
                <div class="qr-pills">
                    <span class="pill-feature">Pencatatan Otomatis</span>
                    <span class="pill-feature">Mendukung Mode Anonim</span>
                    <span class="pill-feature">Konsultasi dan Kunjungan</span>
                </div>

                <!-- Target URL Box -->
                <div class="qr-link-copy">
                    <span id="labelUrlKunjungan">{{ $urlKunjungan }}</span>
                </div>

                <!-- Action Buttons -->
                <div class="btn-group">
                    <a href="{{ $urlKunjungan }}" target="_blank" class="btn btn-primary" style="flex: 1;">
                        Buka Formulir Presensi
                    </a>
                    <a href="{{ route('kunjungan.quick') }}" class="btn btn-outline btn-sm" title="Catat kehadiran anonim langsung">
                        Catat Cepat
                    </a>
                </div>
            </div>

            <!-- QR CODE 2: PLAYGROUND & VIRTUAL ASSISTANT -->
            <div class="card qr-card" id="cardPlayground">
                <span class="qr-badge-num">KODE 02</span>
                <h3 class="qr-title">Playground dan Asisten Virtual</h3>
                <p class="qr-desc">
                    Membuka halaman interaktif untuk menelaah laporan kegiatan, data sampah pesisir, kelompok Bale Resik, keuangan, kawasan wisata Purbayani, serta konsultasi langsung dengan asisten virtual.
                </p>

                <!-- QR Display -->
                <div class="qr-wrapper">
                    <img src="{{ $qrPlayground }}" alt="QR Playground dan Asisten Virtual" class="qr-img" id="imgQrPlayground">
                </div>

                <!-- Feature Pills -->
                <div class="qr-pills">
                    <span class="pill-feature">Tanya Jawab Program</span>
                    <span class="pill-feature">Asisten Virtual</span>
                    <span class="pill-feature">Informasi Kawasan</span>
                </div>

                <!-- Target URL Box -->
                <div class="qr-link-copy">
                    <span id="labelUrlPlayground">{{ $urlPlayground }}</span>
                </div>

                <!-- Action Buttons -->
                <div class="btn-group">
                    <a href="{{ $urlPlayground }}" target="_blank" class="btn btn-primary" style="flex: 1;">
                        Buka Playground
                    </a>
                    <a href="{{ $urlPlayground }}#chat" target="_blank" class="btn btn-outline btn-sm" title="Langsung ke asisten virtual">
                        Mulai Konsultasi
                    </a>
                </div>
            </div>
        </div>

        <!-- STATS COUNTERS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value" id="statTodayCount">{{ $todayVisitsCount }}</div>
                <div class="stat-label">Total Kunjungan Hari Ini</div>
            </div>

            <div class="stat-card">
                <div class="stat-value" id="statIdentifiedCount">{{ $identifiedCount }}</div>
                <div class="stat-label">Tamu Terdaftar</div>
            </div>

            <div class="stat-card">
                <div class="stat-value" id="statAnonCount">{{ $anonymousCount }}</div>
                <div class="stat-label">Tamu Anonim</div>
            </div>

            <div class="stat-card">
                <div class="stat-value" id="statFaqCount">{{ $totalFaqs }}</div>
                <div class="stat-label">Materi Tanya Jawab</div>
            </div>
        </div>

        <!-- LIVE TICKER & CAP HIGHLIGHTS -->
        <div class="bottom-grid">
            <!-- Recent Visitors -->
            <div class="card">
                <div class="section-header">
                    <h3 class="section-title">
                        Daftar Kehadiran Terkini
                    </h3>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <span class="status-pill" style="font-size: 0.72rem; padding: 2px 8px;">Pembaruan Otomatis</span>
                        <a href="{{ route('pengunjung.index') }}" class="btn btn-outline btn-sm" style="padding: 4px 10px; font-size: 0.76rem;">
                            Lihat Semua
                        </a>
                    </div>
                </div>

                <div class="ticker-list" id="tickerList">
                    @forelse($recentVisits as $visit)
                        <div class="ticker-item">
                            <div class="ticker-user">
                                <div class="ticker-avatar">
                                    {{ $visit->is_anonymous ? 'A' : strtoupper(substr($visit->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="ticker-name">
                                        {{ $visit->is_anonymous ? 'Tamu Anonim' : $visit->nama }}
                                    </div>
                                    <div class="ticker-cat">
                                        {{ $visit->jenis_kunjungan }}
                                        @if($visit->instansi) • {{ $visit->instansi }} @endif
                                    </div>
                                </div>
                            </div>
                            <div class="ticker-meta">
                                <span class="ticker-ticket">{{ $visit->ticket_code }}</span>
                                <div class="ticker-time">{{ $visit->created_at->format('H:i') }} ({{ $visit->created_at->diffForHumans() }})</div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 24px; color: var(--text-dim); font-size: 0.9rem;">
                            Belum ada kunjungan yang tercatat hari ini. Silakan pindai kode untuk mengisi presensi.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- CAP 2026 Key Highlights -->
            <div class="card">
                <div class="section-header">
                    <h3 class="section-title">
                        Ringkasan Program CAP 2026
                    </h3>
                    <a href="{{ route('playground.index') }}" class="btn btn-outline btn-sm">Lihat Tanya Jawab</a>
                </div>

                <div class="highlight-list">
                    <div class="highlight-box">
                        <div class="highlight-num">372 kg</div>
                        <div class="highlight-label">Sampah pesisir dikumpulkan dalam aksi Coastal Clean Up (33 peserta).</div>
                    </div>

                    <div class="highlight-box">
                        <div class="highlight-num">12 Orang</div>
                        <div class="highlight-label">Anggota kelompok Bale Resik pengelola fasilitas sampah organik.</div>
                    </div>

                    <div class="highlight-box">
                        <div class="highlight-num">83 Usaha</div>
                        <div class="highlight-label">Unit UMKM tercatat pada direktori informasi kawasan (37 titik lokasi).</div>
                    </div>

                    <div class="highlight-box">
                        <div class="highlight-num">Rp15,6 Juta</div>
                        <div class="highlight-label">Total anggaran yang dilaporkan dalam dokumen pertanggungjawaban.</div>
                    </div>

                    <div class="highlight-box">
                        <div class="highlight-num">Sekitar 400 kg</div>
                        <div class="highlight-label">Sampah residu diproses melalui fasilitas tungku minim asap.</div>
                    </div>

                    <div class="highlight-box">
                        <div class="highlight-num">127 Peserta</div>
                        <div class="highlight-label">Peserta didik dan masyarakat penerima manfaat sosialisasi.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HOST / SCANNER IP MODAL -->
<div class="modal-overlay" id="hostModal">
    <div class="modal-card">
        <div class="modal-header">
            <h4 class="modal-title">Pengaturan Alamat Tautan</h4>
            <button type="button" class="modal-close" onclick="closeHostModal()">&times;</button>
        </div>
        <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 16px;">
            Pastikan tautan menggunakan alamat jaringan yang dapat diakses oleh perangkat pemindai.
        </p>

        <div class="form-group">
            <label class="form-label">Pilihan Alamat Tautan:</label>
            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px;">
                <button type="button" class="btn btn-primary btn-sm" onclick="setHostIp('{{ $productionBaseUrl }}')">
                    Domain Resmi (kkn.ihi.my.id)
                </button>
                @if($localLanIp)
                    <button type="button" class="btn btn-outline btn-sm" onclick="setHostIp('{{ $networkBaseUrl }}')">
                        Alamat Jaringan Lokal ({{ $localLanIp }})
                    </button>
                @endif
                <button type="button" class="btn btn-outline btn-sm" onclick="setHostIp('{{ $currentBaseUrl }}')">
                    Alamat Server Saat Ini
                </button>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="customBaseUrlInput">Alamat Tautan Utama:</label>
            <input type="text" id="customBaseUrlInput" class="form-input" value="{{ $effectiveBaseUrl }}" placeholder="http://192.168.1.10:8000">
        </div>

        <button type="button" class="btn btn-primary btn-block" onclick="applyCustomHost()">
            Simpan dan Perbarui Kode
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function fetchLiveStats() {
        fetch('{{ route('screen.stats') }}')
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    const data = res.data;
                    document.getElementById('statTodayCount').textContent = data.todayVisitsCount;
                    document.getElementById('statIdentifiedCount').textContent = data.identifiedCount;
                    document.getElementById('statAnonCount').textContent = data.anonymousCount;
                    document.getElementById('statFaqCount').textContent = data.totalFaqs;

                    const listContainer = document.getElementById('tickerList');
                    if (data.recentVisits.length === 0) {
                        listContainer.innerHTML = `
                            <div style="text-align: center; padding: 24px; color: var(--text-dim); font-size: 0.9rem;">
                                Belum ada kunjungan yang tercatat hari ini. Silakan pindai kode untuk mengisi presensi.
                            </div>
                        `;
                    } else {
                        let html = '';
                        data.recentVisits.forEach(v => {
                            const initial = v.is_anonymous ? 'A' : (v.nama.charAt(0) || 'U').toUpperCase();
                            html += `
                                <div class="ticker-item">
                                    <div class="ticker-user">
                                        <div class="ticker-avatar">${initial}</div>
                                        <div>
                                            <div class="ticker-name">${v.nama}</div>
                                            <div class="ticker-cat">${v.jenis_kunjungan}</div>
                                        </div>
                                    </div>
                                    <div class="ticker-meta">
                                        <span class="ticker-ticket">${v.ticket_code || '-'}</span>
                                        <div class="ticker-time">${v.time} (${v.time_ago})</div>
                                    </div>
                                </div>
                            `;
                        });
                        listContainer.innerHTML = html;
                    }
                }
            })
            .catch(err => console.error(err));
    }

    setInterval(fetchLiveStats, 4000);

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                alert(`Layar penuh tidak didukung: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    }

    function openHostModal() {
        document.getElementById('hostModal').classList.add('open');
    }

    function closeHostModal() {
        document.getElementById('hostModal').classList.remove('open');
    }

    function setHostIp(url) {
        document.getElementById('customBaseUrlInput').value = url;
    }

    function applyCustomHost() {
        let base = document.getElementById('customBaseUrlInput').value.trim();
        if (!base) return;
        base = base.replace(/\/+$/, '');

        const urlKunjungan = base + '/kunjungan';
        const urlPlayground = base + '/playground';

        document.getElementById('labelUrlKunjungan').textContent = urlKunjungan;
        document.getElementById('labelUrlPlayground').textContent = urlPlayground;

        fetch(`{{ route('screen.qr') }}?url=${encodeURIComponent(urlKunjungan)}`)
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    document.getElementById('imgQrKunjungan').src = res.data_uri;
                }
            });

        fetch(`{{ route('screen.qr') }}?url=${encodeURIComponent(urlPlayground)}`)
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    document.getElementById('imgQrPlayground').src = res.data_uri;
                }
            });

        closeHostModal();
    }
</script>
@endpush
