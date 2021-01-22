<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spm', function (Blueprint $table) {
            $table->id();
            $table->string('no_spm',100)->nullable();
            $table->datetime('tgl_spm')->nullable();
            $table->string('nama_pemohon',100)->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_input')->nullable();
            $table->foreign('user_input')->references('id')->on('users');
            $table->unsignedBigInteger('user_update')->nullable();
            $table->foreign('user_update')->references('id')->on('users');

            $table->string('flag_verif_site_manager',1)->nullable();
            $table->unsignedBigInteger('user_verif_site_manager')->nullable();
            $table->foreign('user_verif_site_manager')->references('id')->on('users');
            $table->datetime('tgl_verif_site_manager')->nullable();

            $table->string('flag_verif_komersial',1)->nullable();
            $table->unsignedBigInteger('user_verif_komersial')->nullable();
            $table->foreign('user_verif_komersial')->references('id')->on('users');
            $table->datetime('tgl_verif_komersial')->nullable();

            $table->string('flag_verif_pm',1)->nullable();
            $table->unsignedBigInteger('user_verif_pm')->nullable();
            $table->foreign('user_verif_pm')->references('id')->on('users');
            $table->datetime('tgl_verif_pm')->nullable();

            // $table->string('flag_ambil_barang',1)->default('N')->nullable();
            // $table->unsignedBigInteger('user_ambil_barang')->nullable();
            // $table->foreign('user_ambil_barang')->references('id')->on('users');
            // $table->datetime('tgl_ambil_barang')->nullable();

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
        Schema::dropIfExists('spm');
    }
}
