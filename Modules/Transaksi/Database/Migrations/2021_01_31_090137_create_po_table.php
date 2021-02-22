<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survei_id')->nullable();
            $table->foreign('survei_id')->references('id')->on('survei');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('supplier');
            $table->string('no_po',100)->nullable();
            $table->datetime('tgl_pengajuan_po')->nullable();
            // $table->string('nama_pemohon',100)->nullable();
            // $table->string('lokasi',100)->nullable();
            // $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_input')->nullable();
            $table->foreign('user_input')->references('id')->on('users');
            $table->unsignedBigInteger('user_update')->nullable();
            $table->foreign('user_update')->references('id')->on('users');
            $table->string('flag_batal',1)->default('N')->nullable();

            $table->string('flag_verif_komersial',1)->nullable();
            $table->unsignedBigInteger('user_verif_komersial')->nullable();
            $table->foreign('user_verif_komersial')->references('id')->on('users');
            $table->datetime('tgl_verif_komersial')->nullable();

            $table->string('flag_verif_pm',1)->nullable();
            $table->unsignedBigInteger('user_verif_pm')->nullable();
            $table->foreign('user_verif_pm')->references('id')->on('users');
            $table->datetime('tgl_verif_pm')->nullable();

            $table->double('total_harga',14,2)->nullable();

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
        Schema::dropIfExists('po');
    }
}
