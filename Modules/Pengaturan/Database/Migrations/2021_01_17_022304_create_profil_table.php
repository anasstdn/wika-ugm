<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profil', function (Blueprint $table) {
            $table->id();
            $table->string('nama',100)->nullable();
            $table->string('nik',100)->nullable();
            $table->unsignedBigInteger('jenis_kelamin')->nullable();
            $table->unsignedBigInteger('agama')->nullable();
            $table->unsignedBigInteger('status_perkawinan')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('kota_domisili',100)->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->string('kota_ktp',100)->nullable();
            $table->string('tempat_lahir',100)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('no_telp',100)->nullable();
            $table->string('email',100)->nullable();
            $table->text('foto')->nullable();
            $table->unsignedBigInteger('user_input')->nullable();
            $table->unsignedBigInteger('user_update')->nullable();

            $table->timestamps();

            $table->foreign('user_input')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_update')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('agama')->references('id')->on('agama')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('jenis_kelamin')->references('id')->on('jenis_kelamin')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_perkawinan')->references('id')->on('status_perkawinan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profil');
    }
}
