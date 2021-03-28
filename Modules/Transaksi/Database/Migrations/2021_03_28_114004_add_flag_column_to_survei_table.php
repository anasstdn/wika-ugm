<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagColumnToSurveiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survei', function (Blueprint $table) {
            $table->string('flag_verif_komersial',1)->nullable();
            $table->unsignedBigInteger('user_verif_komersial')->nullable();
            $table->foreign('user_verif_komersial')->references('id')->on('users');
            $table->datetime('tgl_verif_komersial')->nullable();

            $table->string('flag_verif_pm', 1)->nullable();
            $table->unsignedBigInteger('user_verif_pm')->nullable();
            $table->foreign('user_verif_pm')->references('id')->on('users');
            $table->datetime('tgl_verif_pm')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survei', function (Blueprint $table) {

        });
    }
}
