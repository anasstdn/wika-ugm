<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagColumnToDetailSpmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_spm', function (Blueprint $table) {
             $table->string('flag_verif_site_manager',1)->nullable();
             $table->string('flag_verif_komersial',1)->nullable();
             $table->string('flag_verif_pm',1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_spm', function (Blueprint $table) {

        });
    }
}
