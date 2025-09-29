<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin')
            ]
        );
        User::updateOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'User ABC',
                'password' => Hash::make('user')
            ]
        );
        User::updateOrCreate(
            ['email' => 'user2@example.com'],
            [
                'name' => 'User DEF',
                'password' => Hash::make('user2')
            ]
        );
    }
}
