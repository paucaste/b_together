<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        
        // Crear roles
        $organizacio = Role::create(['name' => 'organizacio']);
        $votant = Role::create(['name' => 'votant']);
        
        // Crear permisos
        $createEncuesta = Permission::create(['name' => 'create encuesta']);
        $voteEncuesta = Permission::create(['name' => 'vote encuesta']);
        
        // Asignar permisos a roles
        $organizacio->givePermissionTo($createEncuesta);
        $votant->givePermissionTo($voteEncuesta);
        
    }
}
