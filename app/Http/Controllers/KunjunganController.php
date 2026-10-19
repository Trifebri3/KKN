<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KunjunganController extends Controller
{
    /**
     * Show visitor check-in page.
     * Automatically registers presence as Anonymous upon QR scan if not already registered in session.
     */
    public function index(Request $request): View
    {
        $activeVisit = null;
        $isJustScanned = false;

        // Check if there is an active visit stored in session
        if (session()->has('active_visit_id')) {
            $activeVisit = Kunjungan::find(session('active_visit_id'));
        }

        // Auto-record Anonymous visit immediately upon scan / fresh access
        if (! $activeVisit || $request->has('baru')) {
            for ($i = 0; $i < 3; $i++) {
                try {
                    $activeVisit = Kunjungan::create([
                        'ticket_code' => Kunjungan::generateUniqueTicketCode(),
                        'nama' => 'Anonymous',
                        'no_telpon' => null,
                        'jenis_kunjungan' => 'Kunjungan Singkat / Anonim',
                        'instansi' => null,
                        'keperluan' => 'Pencatatan otomatis saat pemindaian kode respon cepat (QR)',
                        'is_anonymous' => true,
                        'ip_address' => $request->ip(),
                        'user_agent' => substr((string) $request->userAgent(), 0, 500),
                    ]);
                    $isJustScanned = true;
                    session(['active_visit_id' => $activeVisit->id]);
                    break;
                } catch (UniqueConstraintViolationException $e) {
                    if ($i === 2) {
                        throw $e;
                    }
                }
            }
        }

        return view('kunjungan.index', [
            'visit' => $activeVisit,
            'isJustScanned' => $isJustScanned,
        ]);
    }

    /**
     * Handle visitor check-in form update or submission.
     * Updates the auto-recorded scan visit so no duplicate entry is created.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $rawName = trim((string) $request->input('nama', ''));
        $rawPhone = trim((string) $request->input('no_telpon', ''));
        $rawJenis = trim((string) $request->input('jenis_kunjungan', ''));
        $rawInstansi = trim((string) $request->input('instansi', ''));
        $rawKeperluan = trim((string) $request->input('keperluan', ''));
        $isAnonymousAction = $request->boolean('is_anonymous') || empty($rawName);

        $nama = $isAnonymousAction ? 'Anonymous' : $rawName;
        $jenisKunjungan = ! empty($rawJenis) ? $rawJenis : ($isAnonymousAction ? 'Kunjungan Singkat / Anonim' : 'Konsultasi Program CAP');
        $keperluan = $rawKeperluan ?: ($isAnonymousAction ? 'Kunjungan lokasi dan pemantauan program' : 'Konsultasi dan koordinasi');

        // Check if we can update the active visit from session or hidden input
        $visitId = $request->input('visit_id') ?: session('active_visit_id');
        $kunjungan = $visitId ? Kunjungan::find($visitId) : null;

        if ($kunjungan) {
            $kunjungan->update([
                'nama' => $nama,
                'no_telpon' => $rawPhone ?: null,
                'jenis_kunjungan' => $jenisKunjungan,
                'instansi' => $rawInstansi ?: null,
                'keperluan' => $keperluan,
                'is_anonymous' => $isAnonymousAction,
            ]);
        } else {
            // If session expired, create fresh
            for ($i = 0; $i < 3; $i++) {
                try {
                    $kunjungan = Kunjungan::create([
                        'ticket_code' => Kunjungan::generateUniqueTicketCode(),
                        'nama' => $nama,
                        'no_telpon' => $rawPhone ?: null,
                        'jenis_kunjungan' => $jenisKunjungan,
                        'instansi' => $rawInstansi ?: null,
                        'keperluan' => $keperluan,
                        'is_anonymous' => $isAnonymousAction,
                        'ip_address' => $request->ip(),
                        'user_agent' => substr((string) $request->userAgent(), 0, 500),
                    ]);
                    session(['active_visit_id' => $kunjungan->id]);
                    break;
                } catch (UniqueConstraintViolationException $e) {
                    if ($i === 2) {
                        throw $e;
                    }
                }
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data presensi berhasil diperbarui.',
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
            ->with('success', 'Data presensi berhasil diperbarui.');
    }

    /**
     * Instant one-tap anonymous check-in / reset endpoint.
     */
    public function quick(Request $request): RedirectResponse|JsonResponse
    {
        $kunjungan = null;
        for ($i = 0; $i < 3; $i++) {
            try {
                $kunjungan = Kunjungan::create([
                    'ticket_code' => Kunjungan::generateUniqueTicketCode(),
                    'nama' => 'Anonymous',
                    'no_telpon' => null,
                    'jenis_kunjungan' => 'Kunjungan Singkat / Anonim',
                    'instansi' => null,
                    'keperluan' => 'Pencatatan cepat presensi kunjungan',
                    'is_anonymous' => true,
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 500),
                ]);
                session(['active_visit_id' => $kunjungan->id]);
                break;
            } catch (UniqueConstraintViolationException $e) {
                if ($i === 2) {
                    throw $e;
                }
            }
        }

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
            ->with('success', 'Presensi berhasil dicatat sebagai Tamu Anonim.');
    }
}
