<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
        $users = [
            [
                'name' => 'admin',
                'email' => 'thankyou@gmail.com',
                'password' => '12345678',
                'is_admin' => '2',
            ],
            [
                'name' => env('ADMIN_NAME'),
                'email' => 'ammi@gmail.com',
                'password' => env('ADMIN_PASSWORD'),
                'is_admin' => '1',
            ]
        ];

        foreach($users as $user)
        {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'is_admin' => $user['is_admin']
            ]);
        }
    }
}
