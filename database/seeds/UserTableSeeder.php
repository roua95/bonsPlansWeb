<?php

use Illuminate\Database\Seeder;
use App\role;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role_user = role::where("name", "user")->first();
        $role_admin  = role::where("name", "admin")->first();
        $role_superAdmin  = role::where("name", "superAdmin")->first();

        $user = new User();
        $user->firstname = "user Name";
        $user->lastname = "user LastName";

        $user->email = "user@example.com";
        $user->password = bcrypt("secret");
        $user->save();
        $user->roles()->attach($role_user);

        $admin = new User();
        $admin->firstname = "admin Name";
        $admin->lastname = "admin lastName";
        $admin->email = "admin@example.com";
        $admin->password = bcrypt("secret");
        $admin->save();
        $admin->roles()->attach($role_admin);


        $superAdmin = new User();
        $superAdmin->firstname = "super admin Name";
        $superAdmin->lastname = "super admin lastName";
        $superAdmin->email = "superadmin@example.com";
        $superAdmin->password = bcrypt("secret");
        $superAdmin->save();
        $superAdmin->roles()->attach($role_superAdmin);

    }
}
