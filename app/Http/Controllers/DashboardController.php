<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('costumers.index');
    }
    
    public function homeAdmin()
    {
        return view('dashboardAdm.adm-satu');
    }
    
    public function adminPadang()
    {
        return view('dashboardAdm.adm-satu');
    }
    public function adminSijunjung()
    {
        return view('dashboardAdm.adm-dua');
    }
    public function adminBukittinggi()
    {
        return view('dashboardAdm.adm-tiga');
    }



    


    
}
