<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'role' => 'Admin',
                'password' => Hash::make('masuk123'),
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@gmail.com',
                'role' => 'Manager',
                'password' => Hash::make('masuk123'),
            ],
            [
                'name' => 'Staff User',
                'email' => 'staff@gmail.com',
                'role' => 'Staff',
                'password' => Hash::make('masuk123'),
            ],
            [
                'name' => 'Guest User',
                'email' => 'guest@gmail.com',
                'role' => 'Guest',
                'password' => Hash::make('masuk123'),
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']], // Cek berdasarkan email
                $user // Data yang akan dimasukkan jika belum ada
            );
        }
    }
}
