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
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('costumers.dashboard.padang');
        }
        
        return view('auth.login');
    }

    /**
     * Process customer login
     * 
     * Flow:
     * 1. Validasi input
     * 2. Cari customer berdasarkan name
     * 3. Verifikasi password
     * 4. Login dan redirect ke dashboard sesuai region
     */
    public function login(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'password' => 'required|string|min:6'
        ], [
            'name.required' => 'Nama customer harus diisi',
            'name.min' => 'Nama customer minimal 3 karakter',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter'
        ]);

        // Cari customer berdasarkan name
        $costumer = Costumers::where('name', $validated['name'])->first();

        // Verifikasi customer ada dan password benar
        if (!$costumer || $validated['password'] !== $costumer->password) {
            return back()
                ->withInput($request->only('name'))
                ->withErrors(['loginError' => 'Nama atau password salah']);
        }

        // Login customer dengan guard default 'web'
        Auth::login($costumer);

        // Redirect ke dashboard region sesuai dengan region customer
        $regionRoutes = [
            'padang' => 'costumers.dashboard.padang',
            'sijunjung' => 'costumers.dashboard.sijunjung',
            'bukittinggi' => 'costumers.dashboard.bukittinggi',
        ];

        $route = $regionRoutes[$costumer->region] ?? 'costumers.dashboard.padang';

        return redirect()
            ->route($route)
            ->with('success', "Selamat datang {$costumer->name}!");
    }

    public function register()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('costumers.dashboard.padang');
        }
        
        return view('auth.register');
    }

    /**
     * Store customer baru dengan validasi lengkap
     * 
     * Validasi:
     * - name: unique, min 3 karakter
     * - email: unique, valid email
     * - password: min 8 karakter, hashed sebelum simpan
     * - region: hanya bisa padang, sijunjung, bukittinggi
     * - gender: L atau P
     * - phone: format Indonesia
     * - address: alamat lengkap
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:costumers,name',
            'gender' => 'required|in:L,P',
            'region' => 'required|in:padang,sijunjung,bukittinggi',
            'email' => 'required|email|unique:costumers,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string',
            'address' => 'required|string|max:500',
        ], [
            'name.required' => 'Nama customer harus diisi',
            'name.unique' => 'Nama customer sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'phone.regex' => 'Format nomor telepon tidak valid (mulai dengan 0 atau +62)',
            'region.required' => 'Region harus dipilih',
            'region.in' => 'Region tidak valid',
            'gender.required' => 'Jenis kelamin harus dipilih',
            'gender.in' => 'Jenis kelamin tidak valid',
            'address.required' => 'Alamat harus diisi',
        ]);

        // Jangan hash password - simpan plain text
        // Create customer baru
        Costumers::create($validated);

        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }





    /**
     * Tampilkan halaman login admin
     */
    public function ShowLoginAdmin()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.loginAdmin');
    }

    /**
     * Process admin login dengan validasi yang lebih ketat
     * 
     * Flow:
     * 1. Validasi input (name/email, password)
     * 2. Cari admin berdasarkan name atau email
     * 3. Verifikasi password dan admin harus active
     * 4. Login dan redirect:
     *    - Master admin -> Master Dashboard
     *    - Regional admin -> Dashboard region mereka
     */
    public function adminLogin(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'credential' => 'required|string|min:3', // bisa name atau email
            'password' => 'required|string|min:6'
        ], [
            'credential.required' => 'Nama atau email admin harus diisi',
            'credential.min' => 'Nama atau email minimal 3 karakter',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter'
        ]);

        // Cari admin berdasarkan name atau email
        $admin = Admin::where('name', $validated['credential'])
            ->orWhere('email', $validated['credential'])
            ->where('is_active', true)
            ->first();

        // Verifikasi admin ada, password benar, dan sudah active
        if (!$admin || $validated['password'] !== $admin->password) {
            return back()
                ->withInput($request->only('credential'))
                ->withErrors(['loginError' => 'Nama/email atau password salah, atau akun tidak aktif']);
        }

        // Login admin dengan guard 'admin'
        Auth::guard('admin')->login($admin, $request->boolean('remember'));

        // Redirect ke dashboard sesuai role
        if ($admin->isMaster()) {
            // Master admin -> Master Dashboard
            return redirect()
                ->route('admin.dashboard')
                ->with('success', "Selamat datang Master Admin {$admin->name}! 🎉");
        } else {
            // Regional admin -> Dashboard region mereka
            return redirect()
                ->route('admin.dashboard.region', ['region' => $admin->region])
                ->with('success', "Selamat datang {$admin->name}, Region {$admin->region_label}! 👋");
        }
    }

    /**
     * Tampilkan halaman register admin
     * Note: Sebaiknya register admin hanya bisa dilakukan oleh master admin
     * Untuk sekarang, kita matikan fitur ini dan gunakan seeder saja
     */
    public function adminRegister()
    {
        // Optional: Tambahkan middleware untuk cek master admin saja
        // if (!Auth::guard('admin')->check() || !$this->isMasterAdmin()) {
        //     return redirect()->route('loginAdmin')->with('error', 'Anda tidak memiliki akses');
        // }
        
        return view('auth.RegisterAdmin');
    }

    /**
     * Store admin baru dengan validasi role dan region
     * 
     * Master Admin bisa create:
     * - Regional Admin (dengan region tertentu)
     * - Admin lain (jika diperlukan)
     * 
     * Validasi:
     * - name: unique, min 3 karakter
     * - email: unique, valid email
     * - password: min 8 karakter, hashed sebelum simpan
     * - role: master atau regional
     * - region: hanya untuk regional admin
     */
    public function storeAdmin(Request $request)
    {
        // Hanya master admin yang bisa create admin baru
        $currentAdmin = Auth::guard('admin')->user();
        if (!$currentAdmin || !$currentAdmin->isMaster()) {
            return back()
                ->withErrors(['error' => 'Hanya Master Admin yang dapat membuat akun admin baru']);
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:admins,name',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:master,regional',
            'region' => 'required_if:role,regional|in:padang,sijunjung,bukittinggi',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama admin harus diisi',
            'name.unique' => 'Nama admin sudah terdaftar',
            'name.max' => 'Nama admin maksimal 255 karakter',
            'email.required' => 'Email admin harus diisi',
            'email.unique' => 'Email admin sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role hanya boleh master atau regional',
            'region.required_if' => 'Region harus dipilih untuk admin regional',
            'region.in' => 'Region tidak valid',
        ]);

        // Jika role adalah regional, pastikan region dipilih
        if ($validated['role'] === 'regional' && !isset($validated['region'])) {
            return back()
                ->withErrors(['region' => 'Region harus dipilih untuk admin regional']);
        }

        // Jangan hash password - simpan plain text
        $validated['is_active'] = $validated['is_active'] ?? true;

        // Create admin baru
        $newAdmin = Admin::create($validated);

        return back()
            ->with('success', "Admin '{$newAdmin->name}' berhasil dibuat! Role: {$newAdmin->role_label}");
    }

    /**
     * Logout user dari semua guard
     */
    public function logout(Request $request)
    {
        // Tentukan pesan dan route berdasarkan user type
        $isAdmin = Auth::guard('admin')->check();
        
        if ($isAdmin) {
            Auth::guard('admin')->logout();
            $redirectRoute = 'loginAdmin';
            $message = 'Logout sebagai admin berhasil';
        } else {
            Auth::guard('web')->logout();
            $redirectRoute = 'login';
            $message = 'Logout berhasil';
        }
        
        // Invalidate session dan regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()
            ->route($redirectRoute)
            ->with('success', $message);
    }
}
