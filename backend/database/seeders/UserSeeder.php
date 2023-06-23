<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Bookstore',
            'email' => 'bookstore@bookstore.local',
            'password' => bcrypt('Bookstore@Local'),
            'email_verified_at' => now(),
        ]);
    }
}
