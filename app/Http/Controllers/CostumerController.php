<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CostumerController extends Controller
{
    public function padang()
    {
        return view('costumers.dashboard-padang');
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
