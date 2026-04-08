<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'usuarios',
            'roles',
            'permisos',
            'categorias',
            'items',
            'promociones',
            'ventas',
            'reportes',
        ];

        $actions = ['ver', 'crear', 'editar', 'eliminar'];

        $permissions = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissions[] = Permission::firstOrCreate([
                    'name' => "{$module}.{$action}"
                ]);
            }
        }


        $role = Role::firstOrCreate([
            'name' => 'Super Usuario'
        ]);


        $role->syncPermissions(Permission::all());
    }
}

