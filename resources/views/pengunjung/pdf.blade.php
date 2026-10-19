<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Pengunjung CAP 2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --slate-dark: #0f172a;
            --slate-medium: #334155;
            --slate-light: #64748b;
            --border-color: #cbd5e1;
            --border-light: #e2e8f0;
            --bg-subtle: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #ffffff;
            color: var(--slate-dark);
            line-height: 1.5;
            font-size: 11pt;
            padding: 24px;
        }

        /* Screen Control Bar (Hidden on print) */
        .print-control-bar {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 14px 20px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn {
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid transparent;
        }

        .btn-print {
            background: #0f172a;
            color: #ffffff;
        }

        .btn-print:hover {
            background: #1e293b;
        }

        .btn-back {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #334155;
        }

        .btn-back:hover {
            background: #f1f5f9;
        }

        /* Document Header */
        .doc-header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .doc-org {
            font-size: 9pt;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--slate-light);
            margin-bottom: 4px;
        }

        .doc-title {
            font-size: 16pt;
            font-weight: 800;
            color: var(--slate-dark);
            line-height: 1.25;
            letter-spacing: -0.01em;
        }

        .doc-subtitle {
            font-size: 10pt;
            color: var(--slate-medium);
            margin-top: 4px;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin: 16px 0;
            padding: 12px;
            background: var(--bg-subtle);
            border: 1px solid var(--border-light);
            border-radius: 6px;
        }

        .meta-item label {
            font-size: 8pt;
            text-transform: uppercase;
            color: var(--slate-light);
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }

        .meta-item value {
            font-size: 10pt;
            font-weight: 700;
            color: var(--slate-dark);
        }

        /* Summary Stats Cards */
        .summary-box {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-cell {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 10px 14px;
            background: #ffffff;
        }

        .stat-cell .num {
            font-size: 16pt;
            font-weight: 800;
            color: var(--slate-dark);
            line-height: 1.1;
        }

        .stat-cell .lbl {
            font-size: 8.5pt;
            font-weight: 600;
            color: var(--slate-light);
            margin-top: 4px;
        }

        /* Table */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 24px;
        }

        table.report-table th,
        table.report-table td {
            border: 1px solid var(--border-color);
            padding: 7px 10px;
            text-align: left;
            vertical-align: top;
        }

        table.report-table th {
            background: var(--bg-subtle);
            font-weight: 700;
            color: var(--slate-dark);
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.03em;
        }

        table.report-table tr:nth-child(even) td {
            background: #fafafa;
        }

        .ticket-code {
            font-family: monospace;
            font-weight: 700;
            font-size: 8.5pt;
            color: #0f172a;
        }

        .badge-pill {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: 600;
            border: 1px solid var(--border-color);
        }

        /* Signature Section */
        .signature-section {
            margin-top: 32px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 240px;
            text-align: center;
            font-size: 9pt;
        }

        .sig-space {
            height: 60px;
        }

        .sig-name {
            font-weight: 700;
            border-top: 1px solid #0f172a;
            padding-top: 4px;
        }

        .sig-title {
            font-size: 8pt;
            color: var(--slate-light);
        }

        /* Print Media Styles */
        @media print {
            body {
                padding: 0;
                font-size: 9.5pt;
            }

            .print-control-bar {
                display: none !important;
            }

            @page {
                size: A4 landscape;
                margin: 12mm 15mm;
            }

            table.report-table {
                page-break-inside: auto;
            }

            table.report-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            table.report-table th {
                background: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Screen Control Bar -->
    <div class="print-control-bar">
        <div>
            <strong style="font-size: 0.95rem; color: #0f172a;">Laporan Siap Cetak dan Ekspor PDF</strong>
            <p style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                Klik tombol di sebelah kanan untuk langsung mencetak dokumen atau menyimpannya sebagai berkas PDF.
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="button" class="btn btn-print" onclick="window.print()">
                Cetak / Simpan PDF
            </button>
            <a href="{{ route('pengunjung.index') }}" class="btn btn-back">
                Kembali ke Daftar Pengunjung
            </a>
        </div>
    </div>

    <!-- Official Document Header -->
    <div class="doc-header">
        <div class="doc-org">Collaborative Action Program (CAP) 2026 • Laporan Posko dan Kehadiran</div>
        <h1 class="doc-title">Laporan Rekapitulasi Presensi Pengunjung dan Konsultasi</h1>
        <div class="doc-subtitle">
            Dokumen Resmi Pelaporan, Rekonsiliasi Kehadiran, dan Monitoring Aktivitas Posko Program
        </div>
    </div>

    <!-- Metadata Grid -->
    <div class="meta-grid">
        <div class="meta-item">
            <label>Periode Pelaksanaan</label>
            <value>5 Juli – 5 Agustus 2026</value>
        </div>
        <div class="meta-item">
            <label>Waktu Penarikan Data</label>
            <value>{{ now()->translatedFormat('d F Y, H:i') }} WIB</value>
        </div>
        <div class="meta-item">
            <label>Penyaringan Data</label>
            <value>{{ ucfirst($period ?? 'Semua Waktu') }}</value>
        </div>
        <div class="meta-item">
            <label>Status Dokumen</label>
            <value>Terverifikasi Sistem</value>
        </div>
    </div>

    <!-- Summary Box -->
    <div class="summary-box">
        <div class="stat-cell">
            <div class="num">{{ number_format($totalVisits, 0, ',', '.') }}</div>
            <div class="lbl">Total Seluruh Kunjungan</div>
        </div>
        <div class="stat-cell">
            <div class="num">{{ number_format($todayVisits, 0, ',', '.') }}</div>
            <div class="lbl">Kunjungan Hari Ini</div>
        </div>
        <div class="stat-cell">
            <div class="num">{{ number_format($identifiedCount, 0, ',', '.') }}</div>
            <div class="lbl">Tamu Teridentifikasi (Lengkap)</div>
        </div>
        <div class="stat-cell">
            <div class="num">{{ number_format($anonymousCount, 0, ',', '.') }}</div>
            <div class="lbl">Tamu Anonim (Scan Otomatis)</div>
        </div>
    </div>

    <!-- Table of Visitors -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">No</th>
                <th style="width: 120px;">Nomor Tiket</th>
                <th style="width: 130px;">Waktu Kunjungan</th>
                <th>Nama Pengunjung</th>
                <th style="width: 80px;">Status</th>
                <th style="width: 120px;">Nomor Kontak</th>
                <th style="width: 160px;">Jenis Kunjungan</th>
                <th>Instansi / Lembaga</th>
                <th>Keperluan Kunjungan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kunjungans as $index => $visit)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><span class="ticket-code">{{ $visit->ticket_code }}</span></td>
                    <td>{{ $visit->created_at->format('d/m/Y H:i') }} WIB</td>
                    <td>
                        <strong>{{ $visit->is_anonymous ? 'Tamu Anonim' : $visit->nama }}</strong>
                    </td>
                    <td>
                        <span class="badge-pill">
                            {{ $visit->is_anonymous ? 'Anonim' : 'Terdaftar' }}
                        </span>
                    </td>
                    <td>{{ $visit->no_telpon ?: '-' }}</td>
                    <td>{{ $visit->jenis_kunjungan }}</td>
                    <td>{{ $visit->instansi ?: '-' }}</td>
                    <td>{{ $visit->keperluan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 24px;">
                        Tidak ada data kunjungan yang tercatat untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div>Mengetahui,</div>
            <div style="font-weight: 600;">Koordinator Program CAP 2026</div>
            <div class="sig-space"></div>
            <div class="sig-name">Tim Pelaksana Program</div>
            <div class="sig-title">Collaborative Action Program</div>
        </div>

        <div class="signature-box">
            <div>Dibuat di Posko CAP,</div>
            <div>Petugas Rekonsiliasi & Presensi</div>
            <div class="sig-space"></div>
            <div class="sig-name">Administrasi Posko Informasi</div>
            <div class="sig-title">Pencatatan Kehadiran Pengunjung</div>
        </div>
    </div>
</body>
</html>
