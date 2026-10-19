<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Seed comprehensive QnA knowledge base based on:
     * Collaborative Action Program (CAP) 2026 - Laporan Program & Tinjauan Bukti
     */
    public function run(): void
    {
        $faqs = [
            // Kategori 1: Ringkasan & Metodologi CAP
            [
                'kategori' => 'Ringkasan & Metodologi CAP',
                'pertanyaan' => 'Apa itu Collaborative Action Program (CAP) 2026 dan kapan pelaksanaannya?',
                'jawaban' => 'Collaborative Action Program (CAP) 2026 adalah program intervensi berbasis komunitas yang menggabungkan aksi bersih kawasan pesisir, pengembangan sarana pengelolaan sampah (organik & residu), edukasi masyarakat, penguatan kelompok Bale Resik, serta digitalisasi dan promosi kawasan ecotourism. Program ini dilaksanakan selama 31 hari dari tanggal 5 Juli hingga 5 Agustus 2026.',
                'badge' => 'Penting',
                'urutan' => 1,
                'likes_count' => 58,
            ],
            [
                'kategori' => 'Ringkasan & Metodologi CAP',
                'pertanyaan' => 'Bagaimana siklus kerja 6 tahap yang digunakan dalam pelaksanaan CAP 2026?',
                'jawaban' => 'Pelaksanaan CAP menggunakan kerangka kerja 6 tahap terstruktur:\n1. Discovery: Memahami masalah, aktor, alur sampah, dan kondisi awal.\n2. Prioritization: Memilih persoalan dan intervensi prioritas melalui musyawarah.\n3. Design: Merancang kegiatan, fasilitas, peran, dan RAB.\n4. Small-Scale Test: Menguji kegiatan/fasilitas pada skala terbatas.\n5. Community Validation: Meminta umpan balik pengguna dan masyarakat.\n6. Institutionalization: Menetapkan tata kelola, SOP, SK kelompok, dan rencana keberlanjutan.',
                'badge' => 'Metodologi',
                'urutan' => 2,
                'likes_count' => 45,
            ],
            [
                'kategori' => 'Ringkasan & Metodologi CAP',
                'pertanyaan' => 'Mengapa data laporan diklasifikasikan sebagai "Dilaporkan" dan bukan audit final?',
                'jawaban' => 'Laporan disusun dari informasi yang tersedia dalam percakapan dan ringkasan data lapangan. Dokumen ini bukan opini audit independen. Status "Dilaporkan" digunakan karena angka keluaran fisik, timbangan sampah, dan realisasi keuangan masih memerlukan rekonsiliasi dan verifikasi dokumen primer (tiket timbang, kuitansi, notulen) sebelum dipublikasikan sebagai hasil terverifikasi.',
                'badge' => 'Transparansi',
                'urutan' => 3,
                'likes_count' => 39,
            ],

            // Kategori 2: Sampah, Fasilitas & Bale Resik
            [
                'kategori' => 'Sampah, Fasilitas & Bale Resik',
                'pertanyaan' => 'Berapa volume sampah yang dikumpulkan dalam aksi Coastal Clean Up pesisir?',
                'jawaban' => 'Tercatat sebanyak 372 kg sampah pesisir berhasil dikumpulkan dan dipindahkan ke TPS3R dengan keterlibatan 33 orang peserta aksi bersih. Bukti pendukung yang perlu dilampirkan untuk verifikasi penuh mencakup tiket timbang TPS3R, foto dokumentasi bertanggal, dan daftar hadir peserta.',
                'badge' => 'Output Utama',
                'urutan' => 4,
                'likes_count' => 64,
            ],
            [
                'kategori' => 'Sampah, Fasilitas & Bale Resik',
                'pertanyaan' => 'Apa itu kelompok Bale Resik dan apa peran utamanya?',
                'jawaban' => 'Bale Resik adalah kelompok masyarakat beranggotakan 12 orang yang dibentuk/diperkuat untuk mengelola fasilitas persampahan. Peran utamanya meliputi pengoperasian rumah organik, pemilahan sampah, pengelolaan komposter, pencatatan timbangan, dan pemeliharaan fasilitas.',
                'badge' => 'Komunitas',
                'urutan' => 5,
                'likes_count' => 52,
            ],
            [
                'kategori' => 'Sampah, Fasilitas & Bale Resik',
                'pertanyaan' => 'Fasilitas fisik apa saja yang telah dibangun dan diadakan dalam CAP 2026?',
                'jawaban' => 'Keluaran fisik mencakup:\n- 1 unit bangunan pengelolaan sampah organik\n- 3 unit komposter\n- 1 unit mesin pencacah organik\n- 1 unit fasilitas pengolahan residu (tungku minim asap)\n- 5 set tempat sampah terpilah yang ditempatkan di zona wisata\n- Produksi 17 kg maggot untuk penguraian organik.',
                'badge' => 'Aset Fisik',
                'urutan' => 6,
                'likes_count' => 48,
            ],
            [
                'kategori' => 'Sampah, Fasilitas & Bale Resik',
                'pertanyaan' => 'Bagaimana rekonsiliasi angka 372 kg sampah pesisir, 400 kg residu, dan estimasi 700 kg?',
                'jawaban' => 'Angka-angka ini tidak boleh langsung dijumlahkan karena merujuk pada alur material yang berbeda. Angka 372 kg merupakan sampah aksi pesisir ke TPS3R, sekitar 400 kg adalah sampah residu yang dilaporkan diproses melalui tungku minim asap, dan angka 700 kg adalah estimasi total yang masih memerlukan pemetaan alur agar tidak terjadi penghitungan ganda (double-counting).',
                'badge' => 'Rekonsiliasi',
                'urutan' => 7,
                'likes_count' => 41,
            ],

            // Kategori 3: Digitalisasi & Ekowisata Purbayani
            [
                'kategori' => 'Digitalisasi & Ekowisata Purbayani',
                'pertanyaan' => 'Apa portal web yang dikembangkan dan data apa saja yang tercatat di dalamnya?',
                'jawaban' => 'Situs resmi yang digunakan adalah https://purbayani-eco-tourism.siyota.org/. Portal ini mencatat:\n- Rata-rata ±300 kunjungan web per bulan (selama 2 bulan terakhir)\n- Listing 83 UMKM lokal\n- Pemetaan 37 titik GIS (koordinat wisata & fasilitas)\n- 7 destinasi ekowisata unggulan\n- 5 UMKM yang telah difasilitasi pembayaran digital QRIS.',
                'badge' => 'Digitalisasi',
                'urutan' => 8,
                'likes_count' => 60,
            ],
            [
                'kategori' => 'Digitalisasi & Ekowisata Purbayani',
                'pertanyaan' => 'Bagaimana prinsip perlindungan privasi data UMKM dan masyarakat pada portal web?',
                'jawaban' => 'Data publik dibatasi ketat pada informasi yang telah disetujui pemilik untuk dipublikasikan. Nomor telepon pribadi, koordinat hunian sensitif, dan catatan transaksi individual tidak dipublikasikan secara terbuka guna menjaga keamanan privasi.',
                'badge' => 'Privasi Data',
                'urutan' => 9,
                'likes_count' => 33,
            ],

            // Kategori 4: Keuangan & Anggaran Program
            [
                'kategori' => 'Keuangan & Anggaran Program',
                'pertanyaan' => 'Berapa total anggaran yang dilaporkan dalam program CAP 2026 dan rinciannya?',
                'jawaban' => 'Total anggaran yang dilaporkan adalah Rp15.607.041. Rincian komponen pembiayaan yang tercatat mencakup:\n- Biaya hidup/kit kelompok: Rp8.500.000\n- Kontribusi tambahan anggota: Rp856.000\n- Selisih aritmetis: Rp6.251.041 (hasil hitung selisih yang perlu dicocokkan dengan bukti kuitansi dan buku kas final).',
                'badge' => 'Keuangan',
                'urutan' => 10,
                'likes_count' => 55,
            ],
            [
                'kategori' => 'Keuangan & Anggaran Program',
                'pertanyaan' => 'Dokumen keuangan apa saja yang disiapkan untuk rekonsiliasi dan paket audit?',
                'jawaban' => 'Dokumen mencakup:\n1. Rencana Anggaran Biaya (RAB) awal dan revisi yang disetujui\n2. Buku kas masuk dan keluar bertanggal dengan kode akun\n3. Kuitansi, nota, invoice, dan bukti transfer belanja\n4. Rekonsiliasi kontribusi anggota vs pendanaan mitra\n5. Daftar aset fisik beserta nilai perolehan dan penanggung jawab.',
                'badge' => 'Audit',
                'urutan' => 11,
                'likes_count' => 37,
            ],

            // Kategori 5: Risiko, Safeguards & Mitigasi
            [
                'kategori' => 'Risiko & Safeguards',
                'pertanyaan' => 'Bagaimana mitigasi keselamatan terkait pengoperasian fasilitas tungku minim asap?',
                'jawaban' => 'Istilah "minim asap" merupakan deskripsi lapangan yang memerlukan validasi teknis. Mitigasi keselamatan wajib menerapkan:\n- Standar Operasional Prosedur (SOP) pembakaran bertingkat\n- Penyediaan Alat Pelindung Diri (APD: masker respirator, sarung tangan tahan panas, kacamata pelindung)\n- Pelatihan khusus bagi operator\n- Pemilahan ketat agar bahan beracun berbahaya (B3/plastik PVC) tidak dibakar\n- Evaluasi berkala dengan Dinas Lingkungan Hidup (DLH).',
                'badge' => 'Safeguard',
                'urutan' => 12,
                'likes_count' => 49,
            ],
            [
                'kategori' => 'Risiko & Safeguards',
                'pertanyaan' => 'Berapa banyak peserta yang dijangkau dalam kegiatan edukasi masyarakat?',
                'jawaban' => 'Aktivitas edukasi dan sosialisasi pengelolaan sampah menjangkau sekitar 127 peserta didik dan anggota masyarakat, serta menyasar sekitar 34 kepala keluarga (KK) di kawasan permukiman sekitar lokasi program.',
                'badge' => 'Edukasi',
                'urutan' => 13,
                'likes_count' => 43,
            ],

            // Kategori 6: Keberlanjutan & Presensi
            [
                'kategori' => 'Peta Jalan Keberlanjutan',
                'pertanyaan' => 'Bagaimana peta jalan keberlanjutan program dari 0 hingga 12 bulan ke depan?',
                'jawaban' => 'Roadmap keberlanjutan CAP dibagi dalam 4 fase:\n- 0–30 Hari: Rekonsiliasi data sampah dan keuangan, inventarisasi aset, cek fungsi fasilitas.\n- 31–90 Hari: Penguatan pengurus Bale Resik, penetapan SOP, penerbitan SK desa bila relevan.\n- 3–6 Bulan: Pemetaan kebutuhan B2B (hotel, penginapan, resto), uji minat retribusi layanan sampah mandiri.\n- 6–12 Bulan: Monitoring KPI triwulanan, pemeliharaan rutin aset, evaluasi kemitraan DLH & Yayasan Yota.',
                'badge' => 'Roadmap',
                'urutan' => 14,
                'likes_count' => 51,
            ],
            [
                'kategori' => 'Presensi & Kunjungan Posko',
                'pertanyaan' => 'Bagaimana cara melakukan absensi / presensi kunjungan tamu posko?',
                'jawaban' => 'Tamu dan pengunjung cukup memindai QR Code 1 (QR Presensi Kunjungan) di layar monitor. Pengunjung dapat memasukkan Nama, No. WhatsApp/HP, dan Jenis Kunjungan (Konsultasi Program, Kunjungan Lapangan, Kemitraan, dll). Jika tidak ingin mencantumkan identitas atau sedang terburu-buru, kolom nama dapat dikosongkan atau klik "⚡ Check-in Cepat (Anonim)", dan kehadiran tetap tercatat sebagai Tamu Anonim di MySQL.',
                'badge' => 'Fitur Presensi',
                'urutan' => 15,
                'likes_count' => 62,
            ],
        ];

        // Truncate and re-seed to ensure fresh CAP 2026 content
        Faq::truncate();

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
