<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'dashboard',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-create',
            'permission-create',
        ];

        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }

        $user = User::create([
            'name' => 'IT BERVIN',
            // 'username' => 'IT BERVIN',
            'email' => 'it@bervin.co.id',
            'password' => Hash::make('password'),
        ]);

        $role = Role::create(['name' => 'Superadmin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
