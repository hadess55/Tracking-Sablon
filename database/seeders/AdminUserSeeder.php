<?php

namespace Database\Seeders;

// database/seeders/AdminUserSeeder.php
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder {
    public function run(): void {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin Sablon', 'password' => Hash::make('admin123'), 'is_admin' => true]
        );
    }
}

