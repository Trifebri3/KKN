<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Collaborative Action Program (CAP) 2026')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Clean White Theme Stylesheet with Cache Busting -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ file_exists(public_path('css/style.css')) ? filemtime(public_path('css/style.css')) : time() }}">

    <!-- Critical Navbar & Layout Fallback Styles -->
    <style>
        .nav-content {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }
        .nav-top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            gap: 16px;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding: 4px 0;
            width: 100%;
        }
        .nav-links::-webkit-scrollbar {
            display: none;
        }
        .nav-link {
            font-size: 0.84rem;
            font-weight: 600;
            color: #475569 !important;
            text-decoration: none !important;
            padding: 7px 16px;
            border-radius: 9999px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
            transition: all 0.2s ease;
            display: inline-block;
        }
        .nav-link:hover {
            color: #0f172a !important;
            background: #e2e8f0;
            text-decoration: none !important;
        }
        .nav-link.active {
            color: #ffffff !important;
            background: #2563eb !important;
            border-color: #2563eb !important;
            font-weight: 700;
            text-decoration: none !important;
        }
        @media (min-width: 1024px) {
            .nav-content {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
            .nav-top-row {
                width: auto;
                justify-content: flex-start;
            }
            .nav-links {
                width: auto;
                justify-content: center;
                border-top: none;
                padding: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="page-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="container">
                <div class="nav-content">
                    <div class="nav-top-row">
                        <a href="{{ route('screen.index') }}" class="brand-badge">
                            <div class="brand-logo-mark">CAP</div>
                            <div class="brand-text">
                                <h1>Collaborative Action Program</h1>
                                <p>Laporan Program dan Pusat Informasi</p>
                            </div>
                        </a>

                        <div class="nav-actions">
                            <div class="status-pill">
                                <span class="status-dot"></span>
                                <span>Sistem Aktif</span>
                            </div>

                            <div class="clock-display" id="navClockBox">
                                <span class="clock-time" id="navClockTime">--:-- WIB</span>
                                <span class="clock-date" id="navClockDate">Memuat tanggal...</span>
                            </div>
                        </div>
                    </div>

                    <nav class="nav-links">
                        <a href="{{ route('screen.index') }}" class="nav-link {{ request()->routeIs('screen.*') ? 'active' : '' }}">
                            Layar Utama
                        </a>
                        <a href="{{ route('kunjungan.index') }}" class="nav-link {{ request()->routeIs('kunjungan.*') ? 'active' : '' }}">
                            Buku Tamu
                        </a>
                        <a href="{{ route('playground.index') }}" class="nav-link {{ request()->routeIs('playground.*') ? 'active' : '' }}">
                            Playground & AI
                        </a>
                        <a href="{{ route('pengunjung.index') }}" class="nav-link {{ request()->routeIs('pengunjung.*') ? 'active' : '' }}">
                            Data Pengunjung & Statistik
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) + ' WIB';
            const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            
            const timeEl = document.getElementById('navClockTime');
            const dateEl = document.getElementById('navClockDate');
            if (timeEl) timeEl.textContent = timeStr;
            if (dateEl) dateEl.textContent = dateStr;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    @stack('scripts')
</body>
</html>
