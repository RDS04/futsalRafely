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
        return view('dashboardAdm.admSatu.dashboard');
    }
    public function adminSijunjung()
    {
        return view('dashboardAdm.admDua.dashboard');
    }
    public function adminBukittinggi()
    {
        return view('dashboardAdm.admTiga.dashboard');
    }

    



    public function app(){
        return view('layouts.app');
    }
    public function footer(){
        return view('layouts.footer');
    }
    public function header(){
        return view('layouts.header');
    }
    public function sidebar(){
        return view('layouts.sidebar');
    }
 


    


    
}
