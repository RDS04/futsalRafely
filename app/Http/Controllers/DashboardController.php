<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\Lapangan;
use App\Models\User;
use App\Models\Event;
use App\Models\Slider;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    // Daftar region yang valid - harus sesuai dengan enum di migration
    private $validRegions = ['padang', 'bukittinggi', 'sijunjung'];

    public function index()
    {
        return view('costumers.index');
    }

    /**
     * Dashboard admin dinamis - menampilkan data berdasarkan region yang dipilih
     * Route: /admin/dashboard/{region}
     * 
     * Features:
     * - Multi-region support
     * - Real-time statistics
     * - Access control (regional vs master)
     * - Performance optimization dengan caching
     * 
     * Note:
     * - Master admin bisa view semua region
     * - Regional admin hanya bisa view region mereka sendiri
     */
    public function adminDashboard($region)
    {
        
        $admin = Auth::guard('admin')->user();
        
        // Validasi region
        if (!in_array($region, $this->validRegions)) {
            abort(404, 'Region tidak ditemukan');
        }
        
        // Access control: Regional admin hanya bisa akses region mereka
        if (!$admin->canAccessRegion($region)) {
            abort(403, "Anda tidak memiliki akses ke region {$region}");
        }

        // Ambil data berdasarkan region (dengan caching untuk performa)
        $cacheKey = "dashboard_region_{$region}";
        $dashboardData = Cache::remember($cacheKey, now()->addHour(), function () use ($region) {
            return [
                'lapangan' => Lapangan::where('region', $region)
                    ->select('id', 'namaLapangan', 'jenisLapangan', 'harga', 'status', 'deskripsi')
                    ->get(),
                'boking' => Boking::where('region', $region)
                    ->select('id', 'nama', 'lapangan', 'tanggal', 'jam_mulai', 'jam_selesai', 'status')
                    ->latest()
                    ->get(),
                'events' => Event::where('region', $region)
                    ->select('id', 'judul', 'deskripsi', 'tanggal_mulai', 'status')
                    ->latest()
                    ->limit(10)
                    ->get(),
                'sliders' => Slider::where('region', $region)
                    ->select('id', 'gambar')
                    ->get(),
            ];
        });

        // Hitung statistik
        $lapangan = $dashboardData['lapangan'];
        $boking = $dashboardData['boking'];
        $events = $dashboardData['events'];
        $sliders = $dashboardData['sliders'];
        
        $stats = $this->calculateStats($lapangan, $boking, $events, $sliders);
        
        $regionLabel = $this->getRegionLabel($region);
        $isOwner = $admin->region === $region;

        // Persiapkan data untuk view
        $data = [
            'admin' => $admin,
            'region' => $region,
            'regionLabel' => $regionLabel,
            'isOwner' => $isOwner,
            'lapangan' => $lapangan,
            'boking' => $boking,
            'events' => $events,
            'sliders' => $sliders,
        ] + $stats;

        return view('dashboardAdm.adm-satu', $data);
    }

    /**
     * Master Admin Dashboard - menampilkan ringkasan semua region
     * Route: /admin/dashboard
     * 
     * Features:
     * - Overview semua region
     * - Comparison per region
     * - Trend dan insights
     * - Hanya accessible oleh Master Admin
     */
    public function homeAdmin()
    {
        $admin = Auth::guard('admin')->user();
        
        // Hanya master admin yang bisa akses
        if (!$admin->isMaster()) {
            abort(403, 'Hanya Master Admin yang dapat mengakses halaman ini');
        }
        
        // Ambil data dengan caching (performa optimization)
        $masterData = Cache::remember('master_dashboard_data', now()->addHours(2), function () {
            return [
                'totalLapangan' => Lapangan::count(),
                'totalBoking' => Boking::count(),
                'totalEvents' => Event::count(),
                'totalSliders' => Slider::count(),
                'totalAdmins' => Admin::where('is_active', true)->count(),
                'totalAdminMaster' => Admin::where('role', 'master')->where('is_active', true)->count(),
                'totalAdminRegional' => Admin::where('role', 'regional')->where('is_active', true)->count(),
            ];
        });

        // Data per region dengan detail
        $regionStats = $this->getRegionStats();
        
        // Recent bookings dari semua region (dengan limit 10)
        $recentBookings = Boking::latest()
            ->select('id', 'nama', 'lapangan', 'tanggal', 'region', 'status')
            ->limit(10)
            ->get();
        
        // Top lapangan by bookings (5 teratas)
        $topLapangan = Boking::selectRaw('lapangan, COUNT(*) as booking_count, region')
            ->groupBy('lapangan', 'region')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get();

        // List admin dengan status
        $allAdmins = Admin::where('is_active', true)
            ->select('id', 'name', 'email', 'role', 'region', 'created_at')
            ->orderBy('role', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'admin' => $admin,
            'totalLapangan' => $masterData['totalLapangan'],
            'totalBoking' => $masterData['totalBoking'],
            'totalEvents' => $masterData['totalEvents'],
            'totalSliders' => $masterData['totalSliders'],
            'totalAdmins' => $masterData['totalAdmins'],
            'totalAdminMaster' => $masterData['totalAdminMaster'],
            'totalAdminRegional' => $masterData['totalAdminRegional'],
            'regionStats' => $regionStats,
            'recentBookings' => $recentBookings,
            'topLapangan' => $topLapangan,
            'allAdmins' => $allAdmins,
        ];

        return view('dashboardAdm.master-dashboard', $data);
    }

    /**
     * Helper: Hitung statistik dashboard
     * 
     * @param $lapangan Collection
     * @param $boking Collection
     * @param $events Collection
     * @param $sliders Collection
     * @return array
     */
    private function calculateStats($lapangan, $boking, $events, $sliders)
    {
        return [
            'totalLapangan' => $lapangan->count(),
            'totalBoking' => $boking->count(),
            'totalEvents' => $events->count(),
            'totalSliders' => $sliders->count(),
            'lapanganAktif' => $lapangan->where('status', 'aktif')->count(),
            'lapanganNonaktif' => $lapangan->where('status', 'nonaktif')->count(),
            'bokingConfirmed' => $boking->where('status', 'confirmed')->count(),
            'bokingPending' => $boking->where('status', 'pending')->count(),
            'bokingCancelled' => $boking->where('status', 'cancelled')->count(),
            'recentBookings' => $boking->take(5),
            'recentEvents' => $events->take(5),
        ];
    }

    /**
     * Helper: Ambil statistik per region
     * 
     * @return array
     */
    private function getRegionStats()
    {
        $stats = [];
        foreach ($this->validRegions as $region) {
            $stats[$region] = [
                'lapangan' => Lapangan::where('region', $region)->count(),
                'lapanganAktif' => Lapangan::where('region', $region)->where('status', 'aktif')->count(),
                'boking' => Boking::where('region', $region)->count(),
                'bokingConfirmed' => Boking::where('region', $region)->where('status', 'confirmed')->count(),
                'events' => Event::where('region', $region)->count(),
                'sliders' => Slider::where('region', $region)->count(),
            ];
        }
        return $stats;
    }

    /**
     * Dashboard Region Dinamis - API endpoint untuk AJAX
     * Gunakan untuk menampilkan lapangan berdasarkan region
     */
    public function getRegionData($region)
    {
        $admin = Auth::guard('admin')->user();
        
        // Validasi region
        if (!in_array($region, $this->validRegions)) {
            return response()->json(['error' => 'Region tidak ditemukan'], 404);
        }
        
        // Regional admin hanya bisa akses region mereka
        if ($admin->region !== $region && !$this->isMasterAdmin($admin)) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $lapangan = Lapangan::where('region', $region)->get();
        $boking = Boking::where('region', $region)->get();

        return response()->json([
            'region' => $region,
            'regionLabel' => $this->getRegionLabel($region),
            'lapangan' => $lapangan,
            'boking' => $boking,
            'total_lapangan' => $lapangan->count(),
            'total_boking' => $boking->count(),
        ]);
    }

    /**
     * Helper: Cek apakah admin adalah master admin
     * Sekarang menggunakan role dari kolom admin.role
     * 
     * @param $admin Admin model
     * @return bool
     */
    private function isMasterAdmin($admin)
    {
        // Gunakan method dari Model Admin
        return $admin->isMaster();
    }

    /**
     * Helper: Convert region code ke label yang readable
     * 
     * @param string $region
     * @return string
     */
    private function getRegionLabel($region)
    {
        $labels = [
            'padang' => 'Padang',
            'sijunjung' => 'Sijunjung',
            'bukittinggi' => 'Bukit Tinggi',
        ];
        
        return $labels[$region] ?? ucfirst($region);
    }

    /**
     * Get semua region yang tersedia
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRegions()
    {
        $regions = [];
        foreach ($this->validRegions as $region) {
            $regions[] = [
                'code' => $region,
                'label' => $this->getRegionLabel($region),
            ];
        }
        
        return response()->json($regions);
    }

    public function app()
    {
        return view('layouts.app');
    }
    
    public function footer()
    {
        return view('layouts.footer');
    }
    
    public function header()
    {
        return view('layouts.header');
    }
    
    public function sidebar()
    {
        return view('layouts.sidebar');
    }
}