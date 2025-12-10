<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Costumers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function ShowLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        // Ambil user berdasarkan name
        $costumer = Costumers::where('name', $credentials['name'])->first();

        // Check user ada + verifikasi password hash
        if ($costumer && Hash::check($credentials['password'], $costumer->password)) {
            Auth::login($costumer);

            // redirect sesuai region
            if ($costumer->region === 'padang') {
                return redirect()->route('costumers.dashboard.padang');
            }
            if ($costumer->region === 'sijunjung') {
                return redirect()->route('costumers.dashboard.sijunjung');
            }
            if ($costumer->region === 'bukittinggi') {
                return redirect()->route('costumers.dashboard.bukittinggi');
            }

            return redirect()->route('costumers.dashboard.padang');
        }

        return back()->withErrors(['name' => 'Nama atau password salah']);
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'region' => 'required|in:padang,sijunjung,bukittinggi',
            'email' => 'required|email|unique:costumers,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        Costumers::create($validated);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }





    public function ShowLoginAdmin()
    {
        return view('auth.loginAdmin');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        // Ambil user berdasarkan name
        $admin = Admin::where('name', $credentials['name'])->first();

        // Check user ada + verifikasi password hash
        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            Auth::guard('admin')->login($admin);
            if ($admin->region === 'padang') {
                return redirect()->route('adminPadang');
            }
            if ($admin->region === 'sijunjung') {
                return redirect()->route('adminSijunjung');
            }
            if ($admin->region === 'bukittinggi') {
                return redirect()->route('adminBukittinggi');
            }

            return redirect()->route('adminLogin');
        }

        return back()->withErrors(['name' => 'Nama atau password salah']);
    }

    public function adminRegister()
    {
        return view('auth.RegisterAdmin');
    }
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'region' => 'required|in:padang,sijunjung,bukittinggi',
        ]);

        // Hash password sebelum disimpan
        $validated['password'] = Hash::make($validated['password']);

        Admin::create($validated);
        return redirect()->route('loginAdmin')->with('success', 'Registrasi admin berhasil.');
    }


    public function logout(Request $request)
    {
        // Logout dari semua guard
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
