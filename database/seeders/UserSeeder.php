<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Guru Charan Rajbangshi',
            'email' => 'www.gururaj555@gmail.com',
            'password' => Hash::make('R@j760339'),
            'phone' => '01405792315',
            'role' => 'admin',
            'avatar' => 'avatars/1000215156.jpg',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
    }
}
