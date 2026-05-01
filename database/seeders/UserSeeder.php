<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@ebensupply.co.za'],
            [
                'name'     => 'Eben Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '021 000 0001',
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer@test.co.za'],
            [
                'name'     => 'Test Customer',
                'password' => Hash::make('password'),
                'role'     => 'customer',
                'phone'    => '082 000 0002',
            ]
        );
    }
}