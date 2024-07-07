<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'show users',
            'create users',
            'edit users',
            'delete users',
            ///////////////////////
            'show projects',
            'create projects',
            'edit projects',
            'delete projects',
            ///////////////////////
            'show tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            
            
            // Add more permissions as needed
        ];

        foreach ($permissions as $permission) {
            $existed_permission=Permission::where('name' , $permission)->first();
            if(!$existed_permission){
                Permission::create(['name' => $permission]);
            }
        }

        $roles = [
            'admin',
            'user',
            // Add more roles as needed
        ];

        foreach ($roles as $role) {
            $existed_role=Role::where('name' , $role)->first();
            if(!$existed_role){
                Role::create(['name' => $role]);
            }
        }
        $permissions = Permission::pluck('id', 'id')->all();
        $admin_role = Role::where('name','admin')->first();
        $admin_role->syncPermissions($permissions);
        ////////////////////////create admin////////////////////////
        $admin=User::create([
            'name' => 'General Manager Admin',
            'email' => 'g.m.admin@gmail.com',
            'password' => Hash::make('gmadmin159!48@26#@#$0'),
            
            
        ]);
        $admin->assignRole($admin_role->id);
    }
}