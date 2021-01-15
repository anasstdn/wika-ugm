<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_ids', function (Blueprint $table) {
            $table->id();
            $table->string('config_name',255)->nullable();
            $table->string('table_source',255)->nullable();
            $table->string('config_value',255)->nullable();
            $table->string('description',255)->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_ids');
    }
}
