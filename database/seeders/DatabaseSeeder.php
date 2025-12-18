<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'id' => Str::uuid(),
            'name' => 'Administrator',
            'name_market' => 'Admin Center',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('admin4627'),
            'address' => 'Jl. Pusat Pemerintahan No.1',
            'phone_number' => '081234567890',
        ]);
        $user->createToken('auth_token')->plainTextToken;
    }
}
