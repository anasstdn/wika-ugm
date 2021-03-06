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
        $this->menuActivity();
        $this->menuAcl();
        // $this->menuPengaturan();
        $this->menuMasterdata();
        $this->menuSpm();
        $this->menuPo();
        // $this->menuTransaksiKas();
        // $this->menuSimpanan();
        // $this->menuPinjaman();
        // $this->menuAnggota();
        // $this->menuLaporan();
    }

    private function menuActivity(){
        $this->command->info('Menu Activity Log Seeder');
        $permission = Permission::firstOrNew(array(
            'name'=>'activity-log-list'
        ));
        $permission->guard_name = 'web';
        $permission->save();
        $menu = Menu::firstOrNew(array(
            'name'=>'Riwayat Aktivitas User',
            'permission_id'=>$permission->id,
            'ordinal'=>1,
            'parent_status'=>'N',
            'url' => 'activity-log'
        ));
        $menu->icon = 'si-list';
        $menu->save();
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

    private function menuMasterdata(){
        $this->command->info('Menu Masterdata Seeder');
        $permission = Permission::firstOrNew(array(
            'name'=>'master-data-menu'
        ));
        $permission->guard_name = 'web';
        $permission->save();
        $menu = Menu::firstOrNew(array(
            'name'=>'Masterdata',
            'permission_id'=>$permission->id,
            'ordinal'=>1,
            'parent_status'=>'Y'
        ));
        $menu->icon = 'si-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'material-barang-menu',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Material Barang',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'Y',
        )
    );
        $submenu->save();

          //create SUBMENU master
        $permission = Permission::firstOrNew(array(
            'name'=>'material-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Material',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'material',
        )
    );
        $subsubmenu->save();

           //create SUBMENU master
        $permission = Permission::firstOrNew(array(
            'name'=>'supplier-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Supplier',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'supplier',
        )
    );
        $subsubmenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'stok-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Stok Gudang',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'stok',
        )
    );
        $subsubmenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'kepegawaian-menu',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Kepegawaian',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'Y',
        )
    );
        $submenu->save();

            //create SUBMENU master
        $permission = Permission::firstOrNew(array(
            'name'=>'departement-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Departement',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'departement',
        )
    );
        $subsubmenu->save();

          //create SUBMENU master
        $permission = Permission::firstOrNew(array(
            'name'=>'jabatan-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Jabatan',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'jabatan',
        )
    );
        $subsubmenu->save();

            //create SUBMENU master
        $permission = Permission::firstOrNew(array(
            'name'=>'pegawai-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Data Pegawai',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'pegawai',
        )
    );
        $subsubmenu->save();

    $permission = Permission::firstOrNew(array(
            'name'=>'umum-menu',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Umum',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'Y',
        )
    );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'agama-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Agama',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'agama',
        )
    );
        $subsubmenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'status-perkawinan-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Status Perkawinan',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'status-perkawinan',
        )
    );
        $subsubmenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'jenis-kelamin-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Jenis Kelamin',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'jenis-kelamin',
        )
    );
        $subsubmenu->save();
    }

    private function menuSpm()
    {
        $this->command->info('Menu SPM Seeder');
        $permission = Permission::firstOrNew(array(
            'name'=>'spm-menu'
        ));
        $permission->guard_name = 'web';
        $permission->save();
        $menu = Menu::firstOrNew(array(
            'name'=>'SPM',
            'permission_id'=>$permission->id,
            'ordinal'=>1,
            'parent_status'=>'Y'
        ));
        $menu->icon = 'si-briefcase';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'spm-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Pengajuan SPM',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'N',
            'url' => 'spm'
        )
    );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'verifikasi-spm-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Verifikasi SPM',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'N',
            'url' => 'verifikasi-spm'
        )
    );
        $submenu->save();
    }

    private function menuPo()
    {
        $this->command->info('Menu PO Seeder');
        $permission = Permission::firstOrNew(array(
            'name'=>'po-menu'
        ));
        $permission->guard_name = 'web';
        $permission->save();
        $menu = Menu::firstOrNew(array(
            'name'=>'Purchase Order',
            'permission_id'=>$permission->id,
            'ordinal'=>1,
            'parent_status'=>'Y'
        ));
        $menu->icon = 'si-briefcase';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'survei-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Survei Barang',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'N',
            'url' => 'survei'
        )
    );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'po-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Pengajuan PO',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'N',
            'url' => 'po'
        )
    );
        $submenu->save();

    $permission = Permission::firstOrNew(array(
            'name'=>'verifikasi-po-list',
        ));
        $permission->guard_name = 'web';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Verifikasi PO',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'N',
            'url' => 'verifikasi-po'
        )
    );
        $submenu->save();

    //     $permission = Permission::firstOrNew(array(
    //         'name'=>'verifikasi-spm-list',
    //     ));
    //     $permission->guard_name = 'web';
    //     $permission->save();

    //     $submenu = Menu::firstOrNew(array(
    //         'name'=>'Verifikasi SPM',
    //         'parent_id'=>$menu->id,
    //         'permission_id'=>$permission->id,
    //         'ordinal'=>2,
    //         'parent_status'=>'N',
    //         'url' => 'verifikasi-spm'
    //     )
    // );
    //     $submenu->save();
    }
}

?>