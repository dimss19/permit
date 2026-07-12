<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@inka.co.id',
                'role' => 'superadmin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Divisi Teknik',
                'email' => 'divisi@inka.co.id',
                'role' => 'divisi',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Staff HSE',
                'email' => 'staff@inka.co.id',
                'role' => 'staff',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Manager HSE',
                'email' => 'manager@inka.co.id',
                'role' => 'manager',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Senior Manager HSE',
                'email' => 'seniormanager@inka.co.id',
                'role' => 'senior-manager',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
