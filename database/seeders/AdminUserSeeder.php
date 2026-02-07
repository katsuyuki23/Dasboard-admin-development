<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@panti.com',
            'password' => Hash::make('password'), // Default password
            'role' => 'ADMIN',
        ]);

        User::create([
            'name' => 'Petugas Panti',
            'email' => 'petugas@panti.com',
            'password' => Hash::make('password'),
            'role' => 'PETUGAS',
        ]);
    }
}
