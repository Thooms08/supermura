<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun Affiliator
        User::create([
            'name' => 'Affiliator Pro',
            'email' => 'affiliator@test.com',
            'password' => Hash::make('password123'),
            'role' => 'affiliator',
        ]);

        // 3. Buat Akun Pengunjung (User Biasa)
        User::create([
            'name' => 'Budi Pengunjung',
            'email' => 'user@test.com',
            'password' => Hash::make('password123'),
            'role' => 'pengunjung',
        ]);

        $this->command->info('Seed data berhasil dibuat!');
        $this->command->warn('-----------------------------------');
        $this->command->line('Admin: admin@test.com | password123');
        $this->command->line('Affiliator: affiliator@test.com | password123');
        $this->command->line('Pengunjung: user@test.com | password123');
        $this->command->warn('-----------------------------------');
    }
}