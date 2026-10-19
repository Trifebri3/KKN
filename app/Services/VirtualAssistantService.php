<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\Faq;

class VirtualAssistantService
{
    /**
     * Process message from user and generate intelligent response.
     */
    public function reply(string $userMessage, string $sessionId): array
    {
        $cleanMsg = trim($userMessage);
        $lowerMsg = strtolower($cleanMsg);

        // 1. Save user message to database
        ChatMessage::create([
            'session_id' => $sessionId,
            'sender' => 'user',
            'message' => $cleanMsg,
        ]);

        // 2. Determine response based on CAP 2026 report & FAQ database
        $botResponse = $this->determineResponse($cleanMsg, $lowerMsg);

        // 3. Save bot response to database
        $botMessageRecord = ChatMessage::create([
            'session_id' => $sessionId,
            'sender' => 'bot',
            'message' => $botResponse['text'],
        ]);

        return [
            'reply' => $botResponse['text'],
            'suggestions' => $botResponse['suggestions'] ?? [],
            'created_at' => $botMessageRecord->created_at->format('H:i'),
        ];
    }

    protected function determineResponse(string $msg, string $lower): array
    {
        // Greetings
        if (preg_match('/\b(halo|hai|hi|hey|selamat pagi|selamat siang|selamat sore|selamat malam|assalamualaikum|sampurasun)\b/i', $lower)) {
            return [
                'text' => "Selamat datang di layanan Asisten Virtual Collaborative Action Program (CAP) 2026.\n\nSaya siap membantu memberikan penjelasan mengenai laporan kegiatan program, rekonsiliasi data sampah, kelompok Bale Resik, direktori ekowisata Purbayani, anggaran keuangan, maupun petunjuk presensi kunjungan. Ada yang ingin Anda ketahui?",
                'suggestions' => [
                    'Berapa sampah yang dikumpulkan?',
                    'Apa itu kelompok Bale Resik?',
                    'Berapa total anggaran program?',
                    'Informasi portal web Purbayani',
                ],
            ];
        }

        // Thanks
        if (preg_match('/\b(terima kasih|makasih|thanks|thank you|matur nuwun|nuhun|tq)\b/i', $lower)) {
            return [
                'text' => 'Sama-sama. Senang dapat membantu Anda memahami Laporan Collaborative Action Program (CAP) 2026. Silakan sampaikan jika ada data atau informasi lain yang perlu diperjelas.',
                'suggestions' => [
                    'Fasilitas fisik yang diadakan',
                    'Prosedur tungku residu',
                    'Peta jalan keberlanjutan CAP',
                ],
            ];
        }

        // Sampah & Aksi Pesisir
        if (str_contains($lower, 'sampah') || str_contains($lower, 'clean up') || str_contains($lower, 'pesisir') || str_contains($lower, 'tps3r') || str_contains($lower, '372') || str_contains($lower, '400') || str_contains($lower, '700')) {
            return [
                'text' => "**Data Pengelolaan Sampah CAP 2026:**\n\n".
                          "- **Sampah Pesisir (Coastal Clean Up):** 372 kg dikumpulkan oleh 33 orang peserta dan dialihkan ke TPS3R.\n".
                          "- **Sampah Residu:** Sekitar 400 kg diproses melalui fasilitas tungku minim asap.\n".
                          "- **Produksi Maggot:** 17 kg maggot dihasilkan untuk penguraian bahan organik.\n".
                          '- **Catatan Rekonsiliasi:** Angka total estimasi sekitar 700 kg tidak dapat langsung dijumlahkan tanpa verifikasi alur material, guna mencegah penghitungan ganda (double-counting).',
                'suggestions' => [
                    'Apa peran kelompok Bale Resik?',
                    'Fasilitas apa saja yang dibangun?',
                    'Bagaimana mitigasi tungku residu?',
                ],
            ];
        }

        // Kelompok Bale Resik
        if (str_contains($lower, 'bale resik') || str_contains($lower, 'kelompok') || str_contains($lower, '12 orang') || str_contains($lower, 'pengelola')) {
            return [
                'text' => "**Profil Kelompok Masyarakat Bale Resik:**\n\n".
                          "- Beranggotakan **12 orang** warga lokal.\n".
                          "- Berperan dalam pengoperasian rumah organik, pemilahan sampah, pengelolaan komposter dan maggot, serta pencatatan timbangan.\n".
                          '- Saat ini kelompok dipersiapkan untuk penetapan Standar Operasional Prosedur (SOP) dan Surat Keputusan (SK) formal untuk menjamin tata kelola operasional mandiri.',
                'suggestions' => [
                    'Fasilitas apa saja yang dikelola?',
                    'Berapa anggaran program CAP?',
                    'Peta jalan keberlanjutan Bale Resik',
                ],
            ];
        }

        // Fasilitas Pengelolaan Sampah
        if (str_contains($lower, 'fasilitas') || str_contains($lower, 'komposter') || str_contains($lower, 'pencacah') || str_contains($lower, 'bangunan') || str_contains($lower, 'tempat sampah')) {
            return [
                'text' => "**Inventaris Fasilitas Pengelolaan Sampah CAP 2026:**\n\n".
                          "1. **1 unit Bangunan Pengelolaan Sampah Organik:** Tempat pemilahan, komposting, dan budidaya maggot.\n".
                          "2. **3 unit Komposter:** Untuk fermentasi limbah organik basah.\n".
                          "3. **1 unit Mesin Pencacah Organik:** Membantu percepatan dekomposisi bahan organik.\n".
                          "4. **1 unit Tungku Minim Asap:** Untuk penanganan sampah residu.\n".
                          '5. **5 set Tempat Sampah Terpilah:** Ditempatkan di zona wisata pesisir.',
                'suggestions' => [
                    'Bagaimana keamanan tungku minim asap?',
                    'Berapa maggot yang dihasilkan?',
                    'Berapa total anggaran fasilitas?',
                ],
            ];
        }

        // Digitalisasi, Purbayani & Ekowisata
        if (str_contains($lower, 'web') || str_contains($lower, 'purbayani') || str_contains($lower, 'siyota') || str_contains($lower, 'gis') || str_contains($lower, 'umkm') || str_contains($lower, 'qris') || str_contains($lower, 'destinasi')) {
            return [
                'text' => "**Digitalisasi Kawasan dan Ekowisata (purbayani-eco-tourism.siyota.org):**\n\n".
                          "- **Kunjungan Web:** Rata-rata sekitar 300 kunjungan per bulan selama 2 bulan terakhir.\n".
                          "- **Data Pelaku Usaha:** 83 UMKM lokal terdaftar dalam direktori promosi kawasan.\n".
                          "- **Pemetaan Lokasi:** 37 titik koordinat fasilitas dan objek wisata terpetakan.\n".
                          "- **Destinasi Wisata:** 7 destinasi unggulan dipromosikan.\n".
                          "- **Pembayaran Non-Tunai:** 5 UMKM telah difasilitasi penggunaan QRIS.\n".
                          '- **Prinsip Privasi:** Seluruh data publik dibatasi pada informasi yang telah disetujui untuk dipublikasikan.',
                'suggestions' => [
                    'Berapa anggaran program CAP?',
                    'Apa peran kelompok Bale Resik?',
                    'Berapa peserta edukasi?',
                ],
            ];
        }

        // Keuangan & Anggaran
        if (str_contains($lower, 'anggaran') || str_contains($lower, 'keuangan') || str_contains($lower, 'biaya') || str_contains($lower, 'uang') || str_contains($lower, 'dana') || str_contains($lower, 'rab') || str_contains($lower, '15.607') || str_contains($lower, '8.500')) {
            return [
                'text' => "**Tinjauan Keuangan dan Anggaran CAP 2026:**\n\n".
                          "- **Total Anggaran Dilaporkan:** **Rp15.607.041**\n".
                          "- **Biaya Hidup / Peralatan Kelompok:** Rp8.500.000\n".
                          "- **Kontribusi Tambahan Anggota:** Rp856.000\n".
                          "- **Selisih Aritmetis:** Rp6.251.041\n\n".
                          'Catatan: Nilai tersebut merupakan rekonstruksi hitung berdasarkan data laporan yang sedang diselaraskan dengan bukti kas, nota belanja, dan dokumen primer untuk audit keuangan.',
                'suggestions' => [
                    'Dokumen apa yang disiapkan untuk audit?',
                    'Fasilitas apa saja yang diadakan?',
                    'Berapa sampah pesisir terkumpul?',
                ],
            ];
        }

        // Tungku Minim Asap & Safeguards
        if (str_contains($lower, 'tungku') || str_contains($lower, 'asap') || str_contains($lower, 'emisi') || str_contains($lower, 'safeguard') || str_contains($lower, 'residu') || str_contains($lower, 'bahaya')) {
            return [
                'text' => "**Mitigasi Keselamatan Operasional Tungku Minim Asap:**\n\n".
                          "- Deskripsi 'minim asap' memerlukan pengujian teknis emisi bersama instansi terkait.\n".
                          "- **Prosedur Pengamanan:**\n".
                          "  1. Penerapan Standar Operasional Prosedur (SOP) pembakaran secara ketat.\n".
                          "  2. Pemakaian Alat Pelindung Diri (masker pelindung, sarung tangan tahan panas, pelindung mata).\n".
                          "  3. Operator terlatih serta pencatatan log harian per batch sampah.\n".
                          "  4. Larangan pembakaran limbah berbahaya beracun (B3) dan plastik berklorin.\n".
                          '  5. Koordinasi berkala dengan Dinas Lingkungan Hidup (DLH).',
                'suggestions' => [
                    'Berapa residu yang diproses?',
                    'Apa peran Bale Resik?',
                    'Peta jalan keberlanjutan CAP',
                ],
            ];
        }

        // Edukasi & Komunitas
        if (str_contains($lower, 'edukasi') || str_contains($lower, 'peserta') || str_contains($lower, 'kk') || str_contains($lower, 'warga') || str_contains($lower, '127') || str_contains($lower, '34')) {
            return [
                'text' => "**Cakupan Edukasi dan Partisipasi Masyarakat:**\n\n".
                          "- **Jangkauan Edukasi:** Menjangkau sekitar **127 peserta didik dan masyarakat** melalui pelatihan pengelolaan sampah dan pengenalan ekowisata.\n".
                          "- **Kawasan Permukiman:** Menjangkau sekitar **34 Kepala Keluarga (KK)** di area sasaran kegiatan.\n".
                          '- **Aksi Bersih Kawasan:** Melibatkan 33 orang peserta dalam aksi pembersihan pesisir.',
                'suggestions' => [
                    'Apa peran kelompok Bale Resik?',
                    'Informasi portal web Purbayani',
                    'Berapa sampah yang dikumpulkan?',
                ],
            ];
        }

        // Metodologi & 6 Tahapan CAP
        if (str_contains($lower, 'siklus') || str_contains($lower, 'metode') || str_contains($lower, 'tahap') || str_contains($lower, 'discovery') || str_contains($lower, 'desain')) {
            return [
                'text' => "**Siklus 6 Tahap Pelaksanaan Program:**\n\n".
                          "1. **Discovery:** Pemetaan aktor, observasi awal alur sampah, dan kondisi dasar.\n".
                          "2. **Prioritization:** Musyawarah penentuan kegiatan prioritas bersama warga.\n".
                          "3. **Design:** Penyusunan rencana kerja, desain sarana, dan alokasi anggaran.\n".
                          "4. **Small-Scale Test:** Uji coba fasilitas pada skala terbatas.\n".
                          "5. **Community Validation:** Penjaringan umpan balik pengguna dan berita acara.\n".
                          '6. **Institutionalization:** Formalisasi tata kelola, SOP, dan skema pembiayaan.',
                'suggestions' => [
                    'Peta jalan keberlanjutan 12 bulan',
                    'Berapa total anggaran CAP?',
                    'Mitra apa saja yang terlibat?',
                ],
            ];
        }

        // Peta Jalan / Roadmap Keberlanjutan
        if (str_contains($lower, 'peta jalan') || str_contains($lower, 'roadmap') || str_contains($lower, 'keberlanjutan') || str_contains($lower, 'bulan')) {
            return [
                'text' => "**Peta Jalan Keberlanjutan Program (0 - 12 Bulan):**\n\n".
                          "- **0–30 Hari:** Rekonsiliasi data timbang dan keuangan, verifikasi inventaris, dan pengecekan fungsi fasilitas.\n".
                          "- **31–90 Hari:** Konfirmasi pengurus Bale Resik, penetapan SOP kerja, serta penyusunan dasar hukum lokal jika diperlukan.\n".
                          "- **3–6 Bulan:** Pemetaan kebutuhan layanan B2B bagi hotel dan usaha wisata sekitar, serta uji minat kontribusi mandiri.\n".
                          '- **6–12 Bulan:** Evaluasi indikator capaian secara berkala, pemeliharaan sarana rutin, serta penguatan kemitraan kelembagaan.',
                'suggestions' => [
                    'Apa peran Bale Resik?',
                    'Fasilitas apa saja yang dibangun?',
                    'Dokumen untuk audit keuangan',
                ],
            ];
        }

        // Presensi & QR Kunjungan
        if (str_contains($lower, 'presensi') || str_contains($lower, 'absen') || str_contains($lower, 'qr') || str_contains($lower, 'scan') || str_contains($lower, 'kunjungan') || str_contains($lower, 'hadir')) {
            return [
                'text' => "**Petunjuk Presensi Kunjungan Tamu (Kode 01):**\n\n".
                          "1. Arahkan kamera ponsel Anda ke **Kode Presensi Kunjungan** di layar utama.\n".
                          "2. Buka tautan formulir presensi yang ditampilkan.\n".
                          "3. **Pilihan Pencatatan:**\n".
                          "   - **Formulir Lengkap:** Masukkan Nama, Nomor Kontak, dan Jenis Kunjungan (Konsultasi Program, Kunjungan Lapangan, Kemitraan, atau lainnya).\n".
                          "   - **Catat Cepat (Anonim):** Klik tombol **Catat Cepat sebagai Tamu Anonim** atau biarkan kolom nama kosong. Kehadiran Anda langsung tercatat resmi sebagai **Tamu Anonim** tanpa menyimpan data pribadi.\n".
                          '4. Tiket kunjungan digital akan terbit sebagai tanda verifikasi.',
                'suggestions' => [
                    'Apakah bisa presensi tanpa nama?',
                    'Jenis keperluan kunjungan',
                    'Informasi portal web Purbayani',
                ],
            ];
        }

        // Anonymous presence question
        if (str_contains($lower, 'anonim') || str_contains($lower, 'anonymous') || str_contains($lower, 'tanpa nama') || str_contains($lower, 'privasi')) {
            return [
                'text' => "**Dukungan Presensi Mode Anonim:**\n\n".
                          "Bagi pengunjung yang memerlukan kepraktisan atau memilih untuk tidak mencantumkan data diri, cukup kosongkan kolom nama pada formulir presensi atau gunakan tombol **Catat Cepat sebagai Tamu Anonim**.\n\nSistem secara otomatis akan mencatat kunjungan Anda dengan status **Tamu Anonim**, waktu kunjungan yang tepat, serta menerbitkan kode tiket verifikasi.",
                'suggestions' => [
                    'Petunjuk presensi kunjungan',
                    'Jenis keperluan kunjungan',
                    'Berapa kunjungan hari ini?',
                ],
            ];
        }

        // Search Faq table in Database
        $words = preg_split('/\s+/', $lower, -1, PREG_SPLIT_NO_EMPTY);
        $stopWords = ['apa', 'yang', 'di', 'ke', 'dari', 'dan', 'atau', 'ini', 'itu', 'untuk', 'saya', 'kami', 'bisa', 'bagaimana', 'apakah', 'tentang', 'adalah'];
        $keywords = array_values(array_filter($words, fn ($w) => strlen($w) > 2 && ! in_array($w, $stopWords)));

        if (! empty($keywords)) {
            $query = Faq::where('is_active', true);
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $kw) {
                    $q->orWhere('pertanyaan', 'LIKE', "%{$kw}%")
                        ->orWhere('jawaban', 'LIKE', "%{$kw}%")
                        ->orWhere('kategori', 'LIKE', "%{$kw}%");
                }
            });

            $matchedFaq = $query->first();
            if ($matchedFaq) {
                return [
                    'text' => '**'.$matchedFaq->pertanyaan."**\n\n".$matchedFaq->jawaban,
                    'suggestions' => [
                        'Berapa sampah pesisir terkumpul?',
                        'Apa peran Bale Resik?',
                        'Berapa total anggaran program?',
                    ],
                ];
            }
        }

        // Fallback default
        return [
            'text' => "Mengenai laporan Collaborative Action Program (CAP) 2026, Anda dapat menanyakan tentang data **sampah pesisir (372 kg)**, **kelompok Bale Resik**, **portal purbayani-eco-tourism**, **anggaran program**, **keamanan tungku residu**, maupun **petunjuk presensi kunjungan**.\n\nSilakan pilih salah satu topik di bawah atau sampaikan pertanyaan Anda secara spesifik.",
            'suggestions' => [
                'Berapa sampah yang dikumpulkan?',
                'Apa peran kelompok Bale Resik?',
                'Berapa total anggaran program?',
                'Fasilitas fisik apa saja yang ada?',
            ],
        ];
    }
}
