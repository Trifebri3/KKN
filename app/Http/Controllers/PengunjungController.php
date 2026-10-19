<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengunjungController extends Controller
{
    /**
     * Display the visitor list and statistics dashboard.
     */
    public function index(Request $request): View
    {
        $today = today();

        // 1. Key Statistics
        $totalVisits = Kunjungan::count();
        $todayVisits = Kunjungan::whereDate('created_at', $today)->count();
        $anonymousCount = Kunjungan::where('is_anonymous', true)->count();
        $identifiedCount = $totalVisits - $anonymousCount;

        // 2. Breakdown by visit type (Jenis Kunjungan)
        $jenisDistribution = Kunjungan::select('jenis_kunjungan', DB::raw('count(*) as total'))
            ->groupBy('jenis_kunjungan')
            ->orderByDesc('total')
            ->get();

        // 3. Filter query for the visitors table
        $query = Kunjungan::query();

        // Period filter
        $period = $request->input('periode', 'semua');
        if ($period === 'hari_ini') {
            $query->whereDate('created_at', $today);
        } elseif ($period === '7_hari') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($period === '30_hari') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        // Status filter
        $status = $request->input('status', 'semua');
        if ($status === 'terdaftar') {
            $query->where('is_anonymous', false);
        } elseif ($status === 'anonim') {
            $query->where('is_anonymous', true);
        }

        // Search filter
        if ($request->filled('cari')) {
            $keyword = trim($request->input('cari'));
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('ticket_code', 'LIKE', "%{$keyword}%")
                    ->orWhere('nama', 'LIKE', "%{$keyword}%")
                    ->orWhere('no_telpon', 'LIKE', "%{$keyword}%")
                    ->orWhere('instansi', 'LIKE', "%{$keyword}%")
                    ->orWhere('jenis_kunjungan', 'LIKE', "%{$keyword}%");
            });
        }

        $kunjungans = $query->latest()->paginate(20)->withQueryString();

        return view('pengunjung.index', compact(
            'totalVisits',
            'todayVisits',
            'identifiedCount',
            'anonymousCount',
            'jenisDistribution',
            'kunjungans',
            'period',
            'status'
        ));
    }

    /**
     * Print-ready PDF report view for visitors and summary statistics.
     */
    public function cetak(Request $request): View
    {
        $today = today();
        $totalVisits = Kunjungan::count();
        $todayVisits = Kunjungan::whereDate('created_at', $today)->count();
        $anonymousCount = Kunjungan::where('is_anonymous', true)->count();
        $identifiedCount = $totalVisits - $anonymousCount;

        $jenisDistribution = Kunjungan::select('jenis_kunjungan', DB::raw('count(*) as total'))
            ->groupBy('jenis_kunjungan')
            ->orderByDesc('total')
            ->get();

        // Query visitors
        $query = Kunjungan::query();
        $period = $request->input('periode', 'semua');
        if ($period === 'hari_ini') {
            $query->whereDate('created_at', $today);
        } elseif ($period === '7_hari') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($period === '30_hari') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        $status = $request->input('status', 'semua');
        if ($status === 'terdaftar') {
            $query->where('is_anonymous', false);
        } elseif ($status === 'anonim') {
            $query->where('is_anonymous', true);
        }

        if ($request->filled('cari')) {
            $keyword = trim($request->input('cari'));
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('ticket_code', 'LIKE', "%{$keyword}%")
                    ->orWhere('nama', 'LIKE', "%{$keyword}%")
                    ->orWhere('no_telpon', 'LIKE', "%{$keyword}%")
                    ->orWhere('instansi', 'LIKE', "%{$keyword}%")
                    ->orWhere('jenis_kunjungan', 'LIKE', "%{$keyword}%");
            });
        }

        $kunjungans = $query->latest()->take(500)->get();

        return view('pengunjung.pdf', compact(
            'totalVisits',
            'todayVisits',
            'identifiedCount',
            'anonymousCount',
            'jenisDistribution',
            'kunjungans',
            'period',
            'status'
        ));
    }
}
