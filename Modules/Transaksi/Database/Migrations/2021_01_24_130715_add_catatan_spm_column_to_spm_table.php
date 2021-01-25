<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCatatanSpmColumnToSpmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spm', function (Blueprint $table) {
            $table->text('catatan_site_manager')->nullable();
            $table->text('catatan_project_manager')->nullable();
            $table->text('catatan_komersial')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spm', function (Blueprint $table) {

        });
    }
}
