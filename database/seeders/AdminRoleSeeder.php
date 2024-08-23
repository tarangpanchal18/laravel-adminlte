<?php

namespace Database\Seeders;

use App\Facades\CustomLogger;
use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRoleSeeder extends Seeder
{
    protected $defaultRole;
    protected $defaultAdmin;

    public function __construct() {
        $this->defaultAdmin = Admin::findOrFail(1);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createRoles();
        $this->createPermissions();
        $this->assignRoleToDefaultAdmin();
    }

    public function createRoles() {
        $roles = explode(',', config('app.admin_roles'));

        foreach ($roles as $role) {
            try {
                Role::create(['name' => trim($role), 'guard_name' => 'admin']);
            } catch (\Throwable $th) {
                CustomLogger::write('Seeder::Role', ERROR, $th->getMessage());
            }
        }

        $this->defaultRole = Role::findOrFail(1);
    }

    public function createPermissions()
    {
        $modules = ['users', 'category', 'cmspage', 'website banner'];
        $operations = ['view', 'create', 'update', 'delete'];

        foreach($modules as $module) {
            foreach($operations as $operation) {
                try {
                    $p = Permission::create(['name' => trim($operation .' '. $module), 'guard_name' => 'admin']);
                    $this->defaultRole->givePermissionTo($p);
                } catch (\Throwable $th) {
                    CustomLogger::write('Seeder::Permission', ERROR, $th->getMessage());
                }
            }
        }

        if (config('constants.feature_permission') === true) {
            try {
                $p = Permission::create(['name' => 'view permission module', 'guard_name' => 'admin']);
                $this->defaultRole->givePermissionTo($p);
            } catch (\Throwable $th) {
                CustomLogger::write('Seeder::Permission', ERROR, $th->getMessage());
            }
        }
    }

    public function assignRoleToDefaultAdmin()
    {
        $this->defaultAdmin->assignRole($this->defaultRole);
    }
}
