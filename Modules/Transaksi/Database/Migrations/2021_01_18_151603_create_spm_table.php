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
            $table->boolean('flag_lihat')->default(false)->nullable();
            $table->string('flag_verif_komersial',1)->default('N')->nullable();
            $table->unsignedBigInteger('user_verif_komersial')->nullable();
            $table->foreign('user_verif_komersial')->references('id')->on('users');
            $table->string('flag_verif_pm',1)->default('N')->nullable();
            $table->unsignedBigInteger('user_verif_pm')->nullable();
            $table->foreign('user_verif_pm')->references('id')->on('users');
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
