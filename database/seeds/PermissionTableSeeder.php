<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
// use App\Models\KoperasiUser;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->truncateTables();

        $this->call(CreateAdminUserSeeder::class);
    }

    public function truncateTables()
    {
        Schema::disableForeignKeyConstraints();
        \DB::table('role_has_permissions')->truncate();
        Permission::truncate();
        Role::truncate();
        // KoperasiUser::truncate();
        // \App\User::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
