<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Event;
use App\Models\Slider;
use Illuminate\Http\Request;

class CostumerController extends Controller
{
    public function padang()
    {
        $sliders = Slider::all();
        $lapangan = Lapangan::all();
        $event = Event::all();
        return view('costumers.dashboard-padang', compact('sliders', 'lapangan', 'event'));
    }
    public function sijunjung()
    {
        $sliders = Slider::all();
        $lapangan = Lapangan::all();
        $event = Event::all();    
        return view('costumers.dashboard-sijunjung', compact('sliders', 'lapangan', 'event'));
    }
    public function bukittinggi()
    {
        $sliders = Slider::all();
        $lapangan = Lapangan::all();
        $event = Event::all();
        return view('costumers.dashboard-bukittinggi', compact('sliders', 'lapangan', 'event'));
    }

  
}
