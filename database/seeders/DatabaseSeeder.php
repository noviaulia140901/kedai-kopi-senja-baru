<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'kasir'],
            [
                'name' => 'Kasir Kedai Kopi Senja',
                'email' => 'kasir@kedaikopisenja.test',
                'phone' => null,
                'password' => Hash::make('KasirSenja123'),
                'role' => 'kasir',
                'must_change_password' => false,
            ]
        );

        User::updateOrCreate(
            ['username' => 'manajer'],
            [
                'name' => 'Manajer Kedai Kopi Senja',
                'email' => 'manajer@kedaikopisenja.test',
                'phone' => null,
                'password' => Hash::make('ManajerSenja123'),
                'role' => 'manajer',
                'must_change_password' => false,
            ]
        );
    }
}
