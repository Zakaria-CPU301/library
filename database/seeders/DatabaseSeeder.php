<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'fullname' => 'Super Dede',
            'username' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'role' => 'admin'
        ]);
    }
}
