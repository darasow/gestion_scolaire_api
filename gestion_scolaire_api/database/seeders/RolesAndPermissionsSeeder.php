<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Laratrust\Models\Role;
use Laratrust\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Création des permissions
        $permissions = [
            'all', // permission spéciale
            'create-users',
            'edit-users',
            'delete-users',
            'view-users',
            'view_only',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['display_name' => ucfirst(str_replace('-', ' ', $permission))]
            );
        }

        // Création des rôles
        $admin = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrateur',
                'description' => 'Accès complet au système'
            ]
        );

        $teacher = Role::firstOrCreate(
            ['name' => 'enseignant'],
            [
                'display_name' => 'Enseignant',
                'description' => 'Accès limité aux fonctionnalités enseignants'
            ]
        );

        $visitor = Role::firstOrCreate(
            ['name' => 'visiteur'],
            [
                'display_name' => 'Visiteur',
                'description' => 'Accès en lecture seule'
            ]
        );

        // Attribution des permissions aux rôles
        $admin->permissions()->sync(Permission::all()->pluck('id')); // toutes les permissions
        $teacher->permissions()->sync(
            Permission::where('name', 'view-users')->pluck('id')
        );
        $visitor->permissions()->sync(
            Permission::where('name', 'view_only')->pluck('id')
        );

        // Donner le rôle admin + permission all à l'utilisateur 1
        $adminUser = User::find(1);
        if ($adminUser) {
            $adminUser->roles()->syncWithoutDetaching([$admin->id]);
            $adminUser->permissions()->syncWithoutDetaching(
                Permission::where('name', 'all')->pluck('id')
            );
        }

        // Donner le rôle visiteur + permission view_only à l’utilisateur 2
        $visitorUser = User::find(2);
        if ($visitorUser) {
            $visitorUser->roles()->syncWithoutDetaching([$visitor->id]);
            $visitorUser->permissions()->syncWithoutDetaching(
                Permission::where('name', 'view_only')->pluck('id')
            );
        }
    }
}
