<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;      // <== Pindahin ke sini
use Illuminate\Support\Facades\Hash;    // <== Pindahin ke sini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SiswaSeeder::class);

        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('12345'),
        ]);
    }
}
