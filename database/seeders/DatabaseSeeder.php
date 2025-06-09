<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

public function run(): void
{
    $this->call(SiswaSeeder::class);

    DB::table('users')->insert([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
}


}