<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buscar el rol ya creado en otro seeder
        $superRole = Role::where('name', 'Super Usuario')->first();

        if (!$superRole) {
            $this->command->error('El rol "Super Usuario" no existe. Ejecuta primero el seeder de roles.');
            return;
        }

        // Crear usuario único
        $user = User::updateOrCreate(
            ['email' => 'super@collcapujllay.test'],
            [
                'name' => 'Super Usuario',
                'password' => bcrypt('Super123!'),
                'estado' => true,
            ]
        );

        // Asignar rol
        $user->syncRoles([$superRole]);
    }
}
