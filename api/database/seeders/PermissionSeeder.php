<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Creqate initial permissions
        $permissions = [
            'create company',
            'view company',
            'view all companies',
            'update company',
            'delete company',

            'create employee',
            'view employee',
            'view all employees',
            'update employee',
            'delete employee',
        ];

        foreach($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign existing permissions
        $employeeRole = Role::create(['name' => 'employee']);
        $employeeRole->givePermissionTo('view company');
        $employeeRole->givePermissionTo('view employee');
        $employeeRole->givePermissionTo('update employee');

        Role::create(['name' => 'admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider
    }
}
