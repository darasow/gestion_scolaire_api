<?php

namespace Database\Seeders;
// database/seeders/RolesAndPermissionsSeeder.php
use Illuminate\Database\Seeder;
use Laratrust\Models\Role;
use Laratrust\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Création des permissions
        $permissions = [
            'create-users',
            'edit-users',
            'delete-users',
            'view-users',
            // Ajoutez d'autres permissions selon vos besoins
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'display_name' => ucfirst(str_replace('-', ' ', $permission))]);
        }

        // Création des rôles
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrateur',
            'description' => 'Accès complet au système'
        ]);

        $teacher = Role::create([
            'name' => 'enseignant',
            'display_name' => 'Enseignant',
            'description' => 'Accès limité aux fonctionnalités enseignants'
        ]);

        // Attribution des permissions aux rôles
        $admin->attachPermissions(Permission::all());
        $teacher->attachPermissions(['view-users']);
    }
}
