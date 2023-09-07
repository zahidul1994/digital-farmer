<?php

namespace Database\Seeders;

use App\Models\User;
use App\Helpers\Helper;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateSuperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $info = User::latest()->first();
        if (is_null($info)) {
            $referId = IdGenerator::generate(['table' => 'users', 'field' => 'username', 'length' => 8, 'prefix' => date('Y'), 'reset_on_prefix_change' => true]);
            $superAdmin = new User();
            $superAdmin->name = 'Superadmin';
            $superAdmin->user_type = 'Superadmin';
             $superAdmin->phone = '01739898764';
             $superAdmin->username =  $referId ;
            $superAdmin->email = 'superadmin@gmail.com';
            $superAdmin->password = Hash::make('superadmin1234');
            $superAdmin->created_user_id = '1';
            $superAdmin->updated_user_id = '1';
            $superAdmin->status = '1';
            if ($superAdmin->save()) {
                $profile = new Profile();
                $profile->user_id = 1;
                $profile->gender = 'Male';
                $profile->save();
                $role = Role::create([
                    'name' => 'Superadmin'
                ]);
                $superAdmin->assignRole('Superadmin');
                $permission = Permission::pluck('name');
                $role = Role::wherename('Superadmin')->first();
                $role->syncPermissions($permission);
            }
        } else {
            $superAdmin = User::first();
            $superAdmin->assignRole('Superadmin');
            $permission = Permission::pluck('name');
            $role = Role::wherename('Superadmin')->first();
            $role->syncPermissions($permission);
        }
    }
}
