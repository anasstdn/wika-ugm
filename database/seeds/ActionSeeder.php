<?php

use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	$this->command->info("Hapus Action");
    	// DB::table('action')->truncate();

    	$this->command->info("Simpan Action");
    	$data=array(
    		[
    			'action'=>'Create',
    			'description'=>'Create Data',
    		],
    		[
    			'action'=>'Read',
    			'description'=>'Read Data',
    		],
    		[
    			'action'=>'Update',
    			'description'=>'Update Data',
    		],
    		[
    			'action'=>'Delete',
    			'description'=>'Delete Data',
    		],
    	);

    	if(DB::table('action')->get()->count() == 0){
    		$bar=$this->command->getOutput()->createProgressBar(count($data));
    		foreach($data as $a){
    			\App\Models\Action::firstOrCreate($a);
    			$bar->advance();
    		}
    		$bar->finish();
    	}
    }
}
