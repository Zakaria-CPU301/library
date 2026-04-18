<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Penalty;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Collection::factory()->create([
            'collection_name' => 'karyawan',
        ]);

        User::factory()->createMany([
            [
                'fullname' => 'Super Dede',
                'username' => 'SuperDedeCihuy',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('asd'),
                'role' => 'admin',
                'collection_id' => 1,
            ],
            [
                'fullname' => 'Super User',
                'username' => 'SuperUserCihuy',
                'email' => 'superuser@gmail.com',
                'password' => Hash::make('asd'),
                'role' => 'user',
                'collection_id' => 1,
            ]
        ]);

        Category::factory()->create([
            'category_name' => 'elektronik',
        ]);

        Penalty::factory()->create([
            'penalty_name' => '',
            'nominal_penalty' => 0.0,
        ]);
    }
}
