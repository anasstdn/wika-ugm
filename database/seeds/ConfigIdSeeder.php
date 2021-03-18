<?php

use Illuminate\Database\Seeder;
use App\Models\ConfigId;

class ConfigIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$this->configId();
    }

    private function configId()
    {
    	$this->command->info("Hapus Config IDS");
    	DB::table('config_ids')->truncate();

    	$this->command->info("Simpan Config IDS");
    	$data=array(

    		[
    			'table_source'=>'roles',
    			'config_name'=>'ROLE_ADMIN',
    			'config_value'=>'1,2',
    			'description'=>'Role yang tercatat sebagai Admin',
    		],
    		[
    			'table_source'=>'roles',
    			'config_name'=>'ROLE_DEVELOPER',
    			'config_value'=>1,
    			'description'=>'Role untuk Developer',
    		],
    		[
    			'table_source'=>'roles',
    			'config_name'=>'ROLE_ADMINISTRATOR',
    			'config_value'=>2,
    			'description'=>'Role untuk Admin Sistem',
    		],
            [
                'table_source'=>'roles',
                'config_name'=>'ROLE_PELAKSANA',
                'config_value'=>3,
                'description'=>'Role untuk Pelaksana Sistem',
            ],
            [
                'table_source'=>'roles',
                'config_name'=>'ROLE_SITE_MANAGER',
                'config_value'=>4,
                'description'=>'Role untuk Site Manager Sistem',
            ],
            [
                'table_source'=>'roles',
                'config_name'=>'ROLE_PROJECT_MANAGER',
                'config_value'=>5,
                'description'=>'Role untuk Project Manager Sistem',
            ],
            [
                'table_source'=>'roles',
                'config_name'=>'ROLE_KOMERSIAL',
                'config_value'=>6,
                'description'=>'Role untuk Komersial Sistem',
            ],
            [
                'table_source'=>'roles',
                'config_name'=>'ROLE_PENGADAAN',
                'config_value'=>7,
                'description'=>'Role untuk Pengadaan Sistem',
            ],
             [
                'table_source'=>'roles',
                'config_name'=>'ROLE_GUDANG',
                'config_value'=>8,
                'description'=>'Role untuk Gudang Sistem',
            ],
    		[
    			'table_source'=>'',
    			'config_name'=>'Y-m-d',
    			'config_value'=>'Y-m-d',
    			'description'=>'Date Y-m-d format',
    		],
    		[
    			'table_source'=>'',
    			'config_name'=>'d-m-Y',
    			'config_value'=>'d-m-Y',
    			'description'=>'Date d-m-Y format',
    		],
    		[
    			'table_source'=>'',
    			'config_name'=>'Ymd',
    			'config_value'=>'Ymd',
    			'description'=>'Date ymd format',
    		],
    		[
    			'table_source'=>'',
    			'config_name'=>'dmY',
    			'config_value'=>'dmY',
    			'description'=>'Date dmy format',
    		],
    		[
    			'table_source'=>'',
    			'config_name'=>'d/m/Y',
    			'config_value'=>'d/m/Y',
    			'description'=>'Date d/m/y format',
    		],
    		[
    			'table_source'=>'',
    			'config_name'=>'Y/m/d',
    			'config_value'=>'Y/m/d',
    			'description'=>'Date y/m/d format',
    		],
            [
                'table_source'=>'action',
                'config_name'=>'ACTION_CREATE',
                'config_value'=>1,
                'description'=>'Aksi Create',
            ],
             [
                'table_source'=>'action',
                'config_name'=>'ACTION_READ',
                'config_value'=>2,
                'description'=>'Aksi Read',
            ],
             [
                'table_source'=>'action',
                'config_name'=>'ACTION_UPDATE',
                'config_value'=>3,
                'description'=>'Aksi Update',
            ],
             [
                'table_source'=>'action',
                'config_name'=>'ACTION_DELETE',
                'config_value'=>4,
                'description'=>'Aksi Delete',
            ],
    	);

    	if(DB::table('config_ids')->get()->count() == 0){
    		$bar=$this->command->getOutput()->createProgressBar(count($data));
    		foreach($data as $a){
    			ConfigId::create($a);
    			$bar->advance();
    		}
    		$bar->finish();
    	}
    }
}
