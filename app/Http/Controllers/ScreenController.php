<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Kunjungan;
use chillerlan\QRCode\QRCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScreenController extends Controller
{
    /**
     * Display the main dual-QR display screen.
     */
    public function index(Request $request): View
    {
        $today = today();

        $todayVisitsCount = Kunjungan::whereDate('created_at', $today)->count();
        $anonymousCount = Kunjungan::whereDate('created_at', $today)->where('is_anonymous', true)->count();
        $identifiedCount = $todayVisitsCount - $anonymousCount;
        $totalFaqs = Faq::where('is_active', true)->count();

        $recentVisits = Kunjungan::whereDate('created_at', $today)
            ->latest()
            ->take(8)
            ->get();

        // Production base domain as specified: https://kkn.ihi.my.id
        $productionBaseUrl = config('app.url', 'https://kkn.ihi.my.id');
        if (empty($productionBaseUrl) || str_contains($productionBaseUrl, 'localhost')) {
            $productionBaseUrl = 'https://kkn.ihi.my.id';
        }

        // Current request host for optional local development
        $scheme = $request->getScheme();
        $host = $request->getHost();
        $port = $request->getPort();
        $portSuffix = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';
        $currentBaseUrl = "{$scheme}://{$host}{$portSuffix}";

        // Auto-detect local LAN IP
        $localLanIp = $this->detectLocalIp();
        $networkBaseUrl = $localLanIp ? "{$scheme}://{$localLanIp}{$portSuffix}" : $currentBaseUrl;

        // Default to official production domain: https://kkn.ihi.my.id
        $effectiveBaseUrl = $productionBaseUrl;

        $urlKunjungan = rtrim($effectiveBaseUrl, '/').'/kunjungan';
        $urlPlayground = rtrim($effectiveBaseUrl, '/').'/playground';

        $qrCodeInstance = new QRCode;
        $qrKunjungan = $qrCodeInstance->render($urlKunjungan);
        $qrPlayground = $qrCodeInstance->render($urlPlayground);

        return view('screen.index', compact(
            'todayVisitsCount',
            'anonymousCount',
            'identifiedCount',
            'totalFaqs',
            'recentVisits',
            'urlKunjungan',
            'urlPlayground',
            'qrKunjungan',
            'qrPlayground',
            'effectiveBaseUrl',
            'productionBaseUrl',
            'currentBaseUrl',
            'networkBaseUrl',
            'localLanIp'
        ));
    }

    /**
     * Real-time statistics endpoint for live ticker and visitor counter.
     */
    public function stats(Request $request): JsonResponse
    {
        $today = today();

        $todayVisitsCount = Kunjungan::whereDate('created_at', $today)->count();
        $anonymousCount = Kunjungan::whereDate('created_at', $today)->where('is_anonymous', true)->count();
        $identifiedCount = $todayVisitsCount - $anonymousCount;
        $totalFaqs = Faq::where('is_active', true)->count();

        $recentVisits = Kunjungan::whereDate('created_at', $today)
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'ticket_code' => $v->ticket_code,
                    'nama' => $v->is_anonymous ? 'Tamu Anonim' : $v->nama,
                    'is_anonymous' => $v->is_anonymous,
                    'jenis_kunjungan' => $v->jenis_kunjungan,
                    'time_ago' => $v->created_at->diffForHumans(),
                    'time' => $v->created_at->format('H:i'),
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'todayVisitsCount' => $todayVisitsCount,
                'anonymousCount' => $anonymousCount,
                'identifiedCount' => $identifiedCount,
                'totalFaqs' => $totalFaqs,
                'recentVisits' => $recentVisits,
                'serverTime' => now()->format('H:i:s'),
            ],
        ]);
    }

    /**
     * Dynamic QR generator endpoint for client-side host switcher.
     */
    public function generateQr(Request $request): JsonResponse
    {
        $url = $request->input('url');
        if (empty($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid URL'], 422);
        }

        $dataUri = (new QRCode)->render($url);

        return response()->json([
            'status' => 'success',
            'data_uri' => $dataUri,
            'url' => $url,
        ]);
    }

    /**
     * Helper to detect local LAN IP address.
     */
    protected function detectLocalIp(): ?string
    {
        // Try Windows PowerShell or socket
        $sock = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($sock) {
            if (@socket_connect($sock, '8.8.8.8', 53)) {
                @socket_getsockname($sock, $localIp);
                @socket_close($sock);
                if (! empty($localIp) && $localIp !== '127.0.0.1') {
                    return $localIp;
                }
            }
        }

        $ip = gethostbyname(gethostname());
        if ($ip && $ip !== '127.0.0.1') {
            return $ip;
        }

        return '10.209.206.16';
    }
}
