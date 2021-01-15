<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Menu;
use App\Models\Permission;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->command->info('Delete semua tabel menu');
        Model::unguard();
        Menu::truncate();
        $this->menuAcl();
        // $this->menuPengaturan();
        // $this->menuMasterdata();
        // $this->menuTransaksiKas();
        // $this->menuSimpanan();
        // $this->menuPinjaman();
        // $this->menuAnggota();
        // $this->menuLaporan();
    }

    private function menuAcl(){
    	$this->command->info('Menu ACL Seeder');
    	$permission = Permission::firstOrNew(array(
    		'name'=>'acl-menu'
    	));
    	$permission->guard_name = 'web';
    	$permission->save();
    	$menu = Menu::firstOrNew(array(
    		'name'=>'Access Control List',
    		'permission_id'=>$permission->id,
    		'ordinal'=>1,
    		'parent_status'=>'Y'
    	));
    	$menu->icon = 'si-settings';
    	$menu->save();

          //create SUBMENU master
    	$permission = Permission::firstOrNew(array(
    		'name'=>'user-list',
    	));
    	$permission->guard_name = 'web';
    	$permission->save();

    	$submenu = Menu::firstOrNew(array(
    		'name'=>'Manajemen Pengguna',
    		'parent_id'=>$menu->id,
    		'permission_id'=>$permission->id,
    		'ordinal'=>2,
    		'parent_status'=>'N',
    		'url'=>'user',
    	)
    );
    	$submenu->save();

                  //create SUBMENU master
          $permission = Permission::firstOrNew(array(
            'name'=>'permissions-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Manajemen Permissions',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'N',
            'url'=>'permissions',
        )
    );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'role-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Manajemen Role',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'N',
            'url'=>'roles',
        )
    );
        $submenu->save();
    }
}

?>