<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Schema::disableForeignKeyConstraints();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        Role::truncate();
        Permission::truncate();
        User::truncate();
        Schema::disableForeignKeyConstraints();

        // ===================== Create Permission ====================================

        // dashboard
        Permission::create(['name' => 'view dashboard', 'guard_name' => 'web']);

        // order
        Permission::create(['name' => 'list order', 'guard_name' => 'web']);
        Permission::create(['name' => 'create order', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit order', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete order', 'guard_name' => 'web']);
        
        // order item
        Permission::create(['name' => 'list order-item', 'guard_name' => 'web']);
        Permission::create(['name' => 'create order-item', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit order-item', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete order-item', 'guard_name' => 'web']);
        
        // import file
        Permission::create(['name' => 'list import-file', 'guard_name' => 'web']);
        Permission::create(['name' => 'create import-file', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit import-file', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete import-file', 'guard_name' => 'web']);
        
        // roles
        Permission::create(['name' => 'list role', 'guard_name' => 'web']);
        Permission::create(['name' => 'create role', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit role', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete role', 'guard_name' => 'web']);
        
        // permissions
        Permission::create(['name' => 'list permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'create permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete permission', 'guard_name' => 'web']);

        // users
        Permission::create(['name' => 'list user', 'guard_name' => 'web']);
        Permission::create(['name' => 'create user', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit user', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete user', 'guard_name' => 'web']);
        Permission::create(['name' => 'activate user', 'guard_name' => 'web']);
        Permission::create(['name' => 'deactivate user', 'guard_name' => 'web']);

        // ===================== Assign Role ====================================

        $superadminPermissions = Permission::where('guard_name', 'admin')->get();
        $superadminRole = Role::create(['name' => 'superadmin', 'guard_name' => 'web']);
        $superadminRole->givePermissionTo($superadminPermissions);

        // ===================== Assign Admin ====================================

        $superadmins = [
            [
                'name' => 'Superadmin',
                'email' => 'superadmin@pentacode.id',
                'email_verified_at' => now(),
                'password' => Hash::make('pentacodex'),
            ],
        ];

        foreach($superadmins as $superadmin) {
            $superadmin = User::create([
                'name' => $superadmin['name'],
                'email' => $superadmin['email'],
                'email_verified_at' => $superadmin['email_verified_at'],
                'password' => $superadmin['password'],
            ]);
            $superadmin->assignRole($superadminRole);
        }
    }
}
