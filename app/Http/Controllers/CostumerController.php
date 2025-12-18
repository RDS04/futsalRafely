<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;

class CostumerController extends Controller
{
    public function padang()
    {
        $lapangan = Lapangan::all();
        return view('costumers.dashboard-padang', compact('lapangan'));
    }
    public function sijunjung()
    {
        return view('costumers.dashboard-sijunjung');
    }
    public function bukittinggi()
    {
        return view('costumers.dashboard-bukittinggi');
    }

  
}
