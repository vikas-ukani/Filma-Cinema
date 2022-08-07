<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdatePermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $addPermissions = ['affiliate.settings','affiliate.history','wallet.settings','wallet.history','media-manager.manage','app-settings.appUiShorting','fake.views'];

            foreach($addPermissions as $addPermission){
                 //return $addPermission;
               
                $created_permission = Permission::updateOrCreate(['name' => $addPermission,'guard_name'=>'web']);
                
                $roles = Role::where('name','Super Admin')->get();
                $roles->each(function ($role) use($addPermission) {
        
                    $role->givePermissionTo($addPermission);
        
        
                });
               
            }

    }
}
