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
       
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        
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

        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $opsRole = Role::firstOrCreate(['name' => 'Cajero']);
        $securityRole = Role::firstOrCreate(['name' => 'Encargado']);


        $adminRole->syncPermissions(Permission::all()); 

        $opsRole->syncPermissions([
            'usuarios.ver', 
        ]);

        $securityRole->syncPermissions([
            'usuarios.ver',
            'usuarios.editar', 
        ]);

        
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