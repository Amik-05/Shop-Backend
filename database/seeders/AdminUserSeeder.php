<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'amik.pirov@yandex.ru'],
            [
                'name' => 'Амик',
                'email' => 'amik.pirov@yandex.ru',
                'password' => Hash::make('29731055'),
                'role' => 'admin'
            ]
        );
    }
}
