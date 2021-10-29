<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;
use App\User;
class PermissionTableSeeder extends Seeder
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
		
		$permissions = [
			'list',
			'create',
			'edit',
			'delete',
		];


        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
		
		$role3 = Role::create(['name' => 'Super admin']);
		
		$user = User::create([
            'name' => 'admin',
            'email' => 'admin@openlogicsys.com',
			'password' => Hash::make('Admin@123$!'),
        ]);
        $user->assignRole($role3);
    }
}
