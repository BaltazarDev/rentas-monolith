<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'sistemascreativos@hotmail.com'],
            [
                'name' => 'Administrador',
                'password' => \Illuminate\Support\Facades\Hash::make('Gundam84'),
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'bgdevsoft@gmail.com'],
            [
                'name' => 'Administrador 2',
                'password' => \Illuminate\Support\Facades\Hash::make('#Rentas2531&'),
                'role' => 'super_admin',
            ]
        );
    }
}
