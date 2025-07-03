<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@onelove.org',
            'password' => Hash::make('password'), // Change this in production
            'is_admin' => true,
            'phone' => '1234567890', // provide a value for the phone field
        ]);
        
    }
}
