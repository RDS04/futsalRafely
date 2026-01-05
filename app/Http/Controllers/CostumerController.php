<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Lapangan;
use App\Models\Slider;
use Illuminate\Http\Request;

class CostumerController extends Controller
{
    /**
     * List region yang valid
     */
    private $validRegions = ['padang', 'bukittinggi', 'sijunjung'];

    /**
     * Helper: Validasi dan return region label
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
     * Dashboard Padang - Menampilkan data lapangan, event, slider HANYA region Padang
     * 
     * ⭐ PENTING: 
     * - Query HANYA filter 'padang'
     * - Lapangan status 'aktif' + daftar kolom yang dibutuhkan
     * - Event belum selesai diurutkan paling baru
     * - Slider untuk carousel/banner
     */
    public function padang()
    {
        $region = 'padang';

        // ✅ Filter berdasarkan region
        $sliders = Slider::where('region', $region)
            ->select('id', 'gambar', 'region')
            ->get();

        $lapangan = Lapangan::where('region', $region)
            ->where('status', 'aktif')
            ->select('id', 'namaLapangan', 'jenisLapangan', 'harga', 'deskripsi', 'gambar', 'status', 'region')
            ->get();

        // Event yang belum selesai, urutkan berdasarkan tanggal_mulai terbaru
        $event = Event::where('region', $region)
            ->whereIn('status', ['akan_datang', 'berlangsung'])
            ->select('id', 'judul', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi', 'gambar', 'status', 'region')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('costumers.dashboard-padang', compact('sliders', 'lapangan', 'event'));
    }

    /**
     * Dashboard Sijunjung - Menampilkan data lapangan, event, slider HANYA region Sijunjung
     */
    public function sijunjung()
    {
        $region = 'sijunjung';

        // ✅ Filter berdasarkan region
        $sliders = Slider::where('region', $region)
            ->select('id', 'gambar', 'region')
            ->get();

        $lapangan = Lapangan::where('region', $region)
            ->where('status', 'aktif')
            ->select('id', 'namaLapangan', 'jenisLapangan', 'harga', 'deskripsi', 'gambar', 'status', 'region')
            ->get();

        // Event yang belum selesai, urutkan berdasarkan tanggal_mulai terbaru
        $event = Event::where('region', $region)
            ->whereIn('status', ['akan_datang', 'berlangsung'])
            ->select('id', 'judul', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi', 'gambar', 'status', 'region')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('costumers.dashboard-sijunjung', compact('sliders', 'lapangan', 'event'));
    }

    /**
     * Dashboard Bukit Tinggi - Menampilkan data lapangan, event, slider HANYA region Bukit Tinggi
     */
    public function bukittinggi()
    {
        $region = 'bukittinggi';

        // ✅ Filter berdasarkan region
        $sliders = Slider::where('region', $region)
            ->select('id', 'gambar', 'region')
            ->get();

        $lapangan = Lapangan::where('region', $region)
            ->where('status', 'aktif')
            ->select('id', 'namaLapangan', 'jenisLapangan', 'harga', 'deskripsi', 'gambar', 'status', 'region')
            ->get();

        // Event yang belum selesai, urutkan berdasarkan tanggal_mulai terbaru
        $event = Event::where('region', $region)
            ->whereIn('status', ['akan_datang', 'berlangsung'])
            ->select('id', 'judul', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi', 'gambar', 'status', 'region')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('costumers.dashboard-bukittinggi', compact('sliders', 'lapangan', 'event'));
    }
}
