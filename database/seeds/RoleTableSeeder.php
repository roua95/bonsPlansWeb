<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role_user = new role();
        $role_user->name = "user";
        $role_user->save();

        $role_admin = new role();
        $role_admin->name = "admin";
        $role_admin->save();

        $role_superAdmin = new role();
        $role_superAdmin->name = "superAdmin";
        $role_superAdmin->save();
    }
}
