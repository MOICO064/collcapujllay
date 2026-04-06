<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 Crear roles (si no existen)
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $opsRole = Role::firstOrCreate(['name' => 'Cajero']);
        $securityRole = Role::firstOrCreate(['name' => 'Encargado']);

        // 👤 Usuario Admin
        $admin = User::factory()->create([
            'name' => 'Director Parque',
            'email' => 'admin@collcapujllay.test',
            'password' => 'Secret123!',
            'estado' => true,
        ]);
        $admin->assignRole($adminRole);

        // 👤 Usuario Operaciones
        $ops = User::factory()->create([
            'name' => 'Empleado Operaciones',
            'email' => 'ops@collcapujllay.test',
            'password' => 'OpsAccess1!',
            'estado' => true,
        ]);
        $ops->assignRole($opsRole);

        // 👤 Usuario Seguridad
        $security = User::factory()->create([
            'name' => 'Vigilancia',
            'email' => 'security@collcapujllay.test',
            'password' => 'Secure123!',
            'estado' => false,
        ]);
        $security->assignRole($securityRole);
    }
}
