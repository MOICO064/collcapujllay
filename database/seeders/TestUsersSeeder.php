<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Director Parque',
            'email' => 'admin@collcapujllay.test',
            'role_label' => 'admin',
            'password' => 'Secret123!',
            'estado' => true,
        ]);

        User::factory()->create([
            'name' => 'Empleado Operaciones',
            'email' => 'ops@collcapujllay.test',
            'role_label' => 'operations',
            'password' => Hash::make('OpsAccess1!'),
            'estado' => true,
        ]);

        User::factory()->create([
            'name' => 'Vigilancia',
            'email' => 'security@collcapujllay.test',
            'role_label' => 'security',
            'password' => Hash::make('Secure123!'),
            'estado' => false,
        ]);
    }
}
