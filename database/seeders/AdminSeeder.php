<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed admin users untuk setiap region
     * 
     * Master Admin (role=master): Bisa lihat semua region
     * Regional Admin (role=regional): Hanya bisa akses region mereka
     * 
     * Credentials (Password Plain Text):
     * - Master: admin@master.com / master123
     * - Padang: admin@padang.com / padang123
     * - Sijunjung: admin@sijunjung.com / sijunjung123
     * - Bukittinggi: admin@bukittinggi.com / bukittinggi123
     */
    public function run(): void
    {
        // ==========================================
        // MASTER ADMIN - Bisa lihat semua region
        // ==========================================
        Admin::firstOrCreate(
            ['email' => 'admin@master.com'],
            [
                'name' => 'Master Admin',
                'email' => 'admin@master.com',
                'password' => 'master123', // Plain text
                'region' => 'padang', // default region, tapi bisa akses semua
                'role' => 'master',
                'is_active' => true,
            ]
        );

        // ==========================================
        // ADMIN REGION PADANG
        // ==========================================
        Admin::firstOrCreate(
            ['email' => 'admin@padang.com'],
            [
                'name' => 'Admin Padang',
                'email' => 'admin@padang.com',
                'password' => 'padang123', // Plain text
                'region' => 'padang',
                'role' => 'regional',
                'is_active' => true,
            ]
        );

        // ==========================================
        // ADMIN REGION SIJUNJUNG
        // ==========================================
        Admin::firstOrCreate(
            ['email' => 'admin@sijunjung.com'],
            [
                'name' => 'Admin Sijunjung',
                'email' => 'admin@sijunjung.com',
                'password' => 'sijunjung123', // Plain text
                'region' => 'sijunjung',
                'role' => 'regional',
                'is_active' => true,
            ]
        );

        // ==========================================
        // ADMIN REGION BUKITTINGGI
        // ==========================================
        Admin::firstOrCreate(
            ['email' => 'admin@bukittinggi.com'],
            [
                'name' => 'Admin Bukittinggi',
                'email' => 'admin@bukittinggi.com',
                'password' => 'bukittinggi123', // Plain text
                'region' => 'bukittinggi',
                'role' => 'regional',
                'is_active' => true,
            ]
        );
    }
}
