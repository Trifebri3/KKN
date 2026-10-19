<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KunjunganController extends Controller
{
    /**
     * Show the visitor attendance check-in form.
     */
    public function index(Request $request): View
    {
        $lastVisit = null;
        if (session()->has('last_visit_id')) {
            $lastVisit = Kunjungan::find(session('last_visit_id'));
        }

        return view('kunjungan.index', compact('lastVisit'));
    }

    /**
     * Handle visitor check-in submission.
     * Records presence as named visitor or Anonymous if fields are empty.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $rawName = trim((string) $request->input('nama', ''));
        $rawPhone = trim((string) $request->input('no_telpon', ''));
        $rawJenis = trim((string) $request->input('jenis_kunjungan', ''));
        $rawInstansi = trim((string) $request->input('instansi', ''));
        $rawKeperluan = trim((string) $request->input('keperluan', ''));
        $isAnonymousAction = $request->boolean('is_anonymous') || empty($rawName);

        // If name is empty, it records as Anonymous
        $nama = $isAnonymousAction ? 'Anonymous' : $rawName;
        $jenisKunjungan = ! empty($rawJenis) ? $rawJenis : ($isAnonymousAction ? 'Kunjungan Singkat / Anonim' : 'Kunjungan Biasa');

        $kunjungan = Kunjungan::create([
            'nama' => $nama,
            'no_telpon' => $rawPhone ?: null,
            'jenis_kunjungan' => $jenisKunjungan,
            'instansi' => $rawInstansi ?: null,
            'keperluan' => $rawKeperluan ?: null,
            'is_anonymous' => $isAnonymousAction,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Presensi berhasil dicatat.',
                'data' => [
                    'id' => $kunjungan->id,
                    'ticket_code' => $kunjungan->ticket_code,
                    'nama' => $kunjungan->is_anonymous ? 'Tamu Anonim' : $kunjungan->nama,
                    'is_anonymous' => $kunjungan->is_anonymous,
                    'jenis_kunjungan' => $kunjungan->jenis_kunjungan,
                    'instansi' => $kunjungan->instansi,
                    'time' => $kunjungan->created_at->format('H:i:s, d M Y'),
                ],
            ]);
        }

        return redirect()->route('kunjungan.index')
            ->with('last_visit_id', $kunjungan->id)
            ->with('success', 'Kehadiran Anda berhasil dicatat.');
    }

    /**
     * Instant one-tap anonymous check-in endpoint.
     */
    public function quick(Request $request): RedirectResponse|JsonResponse
    {
        $kunjungan = Kunjungan::create([
            'nama' => 'Anonymous',
            'no_telpon' => null,
            'jenis_kunjungan' => 'Kunjungan Singkat / Anonim',
            'instansi' => null,
            'keperluan' => 'Pencatatan cepat presensi kunjungan',
            'is_anonymous' => true,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Presensi berhasil dicatat sebagai Tamu Anonim.',
                'data' => [
                    'id' => $kunjungan->id,
                    'ticket_code' => $kunjungan->ticket_code,
                    'nama' => 'Tamu Anonim',
                    'is_anonymous' => true,
                    'jenis_kunjungan' => $kunjungan->jenis_kunjungan,
                    'time' => $kunjungan->created_at->format('H:i:s, d M Y'),
                ],
            ]);
        }

        return redirect()->route('kunjungan.index')
            ->with('last_visit_id', $kunjungan->id)
            ->with('success', 'Presensi berhasil dicatat sebagai Tamu Anonim.');
    }
}
