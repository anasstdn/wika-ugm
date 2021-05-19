<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Profil;
use App\Models\UserProfil;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

        //
    public function run()
    {

    	$config = config('permission_list.role_structure');

        $this->command->info("Create User Data");

        $bar=$this->command->getOutput()->createProgressBar(count($config));
    	foreach($config as $key => $modules)
    	{
    		$role = Role::firstOrCreate(['name' => ucwords(str_replace('_', ' ', $key))]);
    		$role_id = $role->id;
    		foreach($modules as $module => $values)
    		{
    			foreach (explode(',', $values) as $p => $perm) {
    				$permissions = Permission::firstOrCreate(['name' => $perm]);
    				$permission_id[$key][$module+1] = $permissions->id;
    			}
    		}

    		$role->syncPermissions($permission_id[$key]);

    		if($key == 'developer')
    		{
    			$user = User::firstOrNew([
    				'name' => ucwords(str_replace('_', ' ', $key)),
    				'username'  =>  $key, 
    				'email' => $key.'@gmail.com',
    			]);
    			$user->password = bcrypt('maintenis'); 
    			$user->save();
    			$user->assignRole([$role_id]);

                $id_user = $user->id;
    		}
    		else
    		{
    			if($key !== 'developer')
    			{
    				$user = User::firstOrNew([
    					'name' => ucwords(str_replace('_', ' ', $key)), 
    					'username'  =>  $key, 
    					'email' => $key.'@gmail.com',
    				]);
    				$user->password = bcrypt('password');
    				$user->save();
    				$user->assignRole([$role_id]);

                    $id_user = $user->id;
    			}
    		}

            $profil = Profil::firstOrCreate([
                'nama' => ucwords(str_replace('_', ' ', $key)),
                'email' => $key.'@gmail.com'
            ]);

            $user_profil = UserProfil::firstOrCreate([
                'user_id' => $id_user,
                'profil_id' => $profil->id
            ]);

            $bar->advance();
    	}

        $bar->finish();

    }

}


