<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
// database/seeders/PermissionSeeder.php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Permissions
        Permission::firstOrCreate(['name' => 'users.view']);
        Permission::firstOrCreate(['name' => 'users.create']);
        Permission::firstOrCreate(['name' => 'users.edit']);
        Permission::firstOrCreate(['name' => 'users.delete']);

        // Rôles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $agent = Role::firstOrCreate(['name' => 'agent']);

        // Attribuer des permissions aux rôles
        $admin->givePermissionTo(Permission::all());
        $agent->givePermissionTo(['users.view']);

        // Exemple: donner un rôle à un utilisateur
        $user = \App\Models\User::first(); // adapte
        if ($user) $user->assignRole('admin');
    }
}
