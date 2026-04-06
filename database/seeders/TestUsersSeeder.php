<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 Limpiar cache de permisos de Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 🔐 Crear permisos
        $permisos = [
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',

            'roles.ver',
            'roles.crear',
            'roles.editar',
            'roles.eliminar',

            'permisos.ver',
            'permisos.crear',
            'permisos.editar',
            'permisos.eliminar',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // 🔥 Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $opsRole = Role::firstOrCreate(['name' => 'Cajero']);
        $securityRole = Role::firstOrCreate(['name' => 'Encargado']);

        // 🧠 Asignar permisos a roles
        $adminRole->syncPermissions(Permission::all()); // Admin = todos los permisos

        $opsRole->syncPermissions([
            'usuarios.ver', // Cajero solo puede ver usuarios
        ]);

        $securityRole->syncPermissions([
            'usuarios.ver',
            'usuarios.editar', // Encargado puede ver y editar usuarios
        ]);

        // 👤 Crear usuarios (la factory ya hashea el password)
        $admin = User::factory()->create([
            'name' => 'Director Parque',
            'email' => 'admin@collcapujllay.test',
            'password' => 'Secret123!',
            'estado' => true,
        ]);
        $admin->assignRole($adminRole);

        $ops = User::factory()->create([
            'name' => 'Empleado Operaciones',
            'email' => 'ops@collcapujllay.test',
            'password' => 'OpsAccess1!',
            'estado' => true,
        ]);
        $ops->assignRole($opsRole);

        $security = User::factory()->create([
            'name' => 'Vigilancia',
            'email' => 'security@collcapujllay.test',
            'password' => 'Secure123!',
            'estado' => false,
        ]);
        $security->assignRole($securityRole);
    }
}