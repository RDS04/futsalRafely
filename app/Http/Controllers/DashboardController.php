<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('costumers.index');
    }

    /**
     * Dashboard utama admin - menampilkan statistik untuk semua region
     */
    public function homeAdmin()
    {
        $admin = Auth::guard('admin')->user();
        
        // Hanya master admin bisa lihat semua region
        // Untuk sekarang, asumsikan admin dengan ID 1 adalah master
        $isOwner = $admin->id === 1;
        
        if ($isOwner) {
            // Master admin - lihat semua data
            $totalLapangan = Lapangan::count();
            $totalBoking = Boking::count();
        } else {
            // Regional admin - lihat hanya data region mereka
            $totalLapangan = Lapangan::byRegion($admin->region)->count();
            $totalBoking = Boking::byRegion($admin->region)->count();
        }
        
        return view('dashboardAdm.adm-satu', compact('admin', 'totalLapangan', 'totalBoking', 'isOwner'));
    }

    /**
     * Dashboard Padang
     */
    public function adminPadang()
    {
        $admin = Auth::guard('admin')->user();
        
        // Validasi bahwa admin hanya akses region mereka
        if ($admin->region !== 'padang') {
            abort(403, 'Anda tidak memiliki akses ke region ini');
        }

        $lapangan = Lapangan::byRegion('padang')->get();
        $boking = Boking::byRegion('padang')->get();
        
        return view('dashboardAdm.admSatu.dashboard', compact('lapangan', 'boking', 'admin'));
    }

    /**
     * Dashboard Sijunjung
     */
    public function adminSijunjung()
    {
        $admin = Auth::guard('admin')->user();
        
        // Validasi bahwa admin hanya akses region mereka
        if ($admin->region !== 'sijunjung') {
            abort(403, 'Anda tidak memiliki akses ke region ini');
        }

        $lapangan = Lapangan::byRegion('sijunjung')->get();
        $boking = Boking::byRegion('sijunjung')->get();
        
        return view('dashboardAdm.admDua.dashboard', compact('lapangan', 'boking', 'admin'));
    }

    /**
     * Dashboard Bukittinggi
     */
    public function adminBukittinggi()
    {
        $admin = Auth::guard('admin')->user();
        
        // Validasi bahwa admin hanya akses region mereka
        if ($admin->region !== 'bukittinggi') {
            abort(403, 'Anda tidak memiliki akses ke region ini');
        }

        $lapangan = Lapangan::byRegion('bukittinggi')->get();
        $boking = Boking::byRegion('bukittinggi')->get();
        
        return view('dashboardAdm.admTiga.dashboard', compact('lapangan', 'boking', 'admin'));
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