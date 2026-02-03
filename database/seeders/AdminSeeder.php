<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'username' => 'BARIGYE DAVIS',
            'password' => Hash::make('BARIGYE@!@1'), // Always store hashed!
            'role' => 'super', // or 'admin', etc.
            'email' => 'barigyedavis6@gmail.com',
            'contact' => '+256752088768',
            'address' => '123 Admin Lane, Admin City',
            'verification_code' => Str::random(6),
            'is_verified' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}