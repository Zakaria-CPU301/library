<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
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

        User::factory()->create([
            'fullname' => 'Super Dede',
            'username' => 'SuperDedeCihuy',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('asd'),
            'role' => 'admin',
            'collection_id' => 1,
        ]);

        Category::factory()->createMany([
            ['category_name' => 'Novel'],
            ['category_name' => 'Science'],
            ['category_name' => 'History'],
            ['category_name' => 'Technology'],
            ['category_name' => 'Art'],
        ]);
    }
}
