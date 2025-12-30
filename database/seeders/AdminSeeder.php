<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Master - bisa lihat semua region
        Admin::firstOrCreate(
            ['name' => 'Master Admin'],
            [
                'name' => 'Master Admin',
                'password' => Hash::make('admin123'),
                'region' => 'padang', // default
            ]
        );

        // Admin Padang
        Admin::firstOrCreate(
            ['name' => 'Admin Padang'],
            [
                'name' => 'Admin Padang',
                'password' => Hash::make('padang123'),
                'region' => 'padang',
            ]
        );

        // Admin Sijunjung
        Admin::firstOrCreate(
            ['name' => 'Admin Sijunjung'],
            [
                'name' => 'Admin Sijunjung',
                'password' => Hash::make('sijunjung123'),
                'region' => 'sijunjung',
            ]
        );

        // Admin Bukittinggi
        Admin::firstOrCreate(
            ['name' => 'Admin Bukittinggi'],
            [
                'name' => 'Admin Bukittinggi',
                'password' => Hash::make('bukittinggi123'),
                'region' => 'bukittinggi',
            ]
        );
    }
}
