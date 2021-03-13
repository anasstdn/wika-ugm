<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBapbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bapb', function (Blueprint $table) {
            $table->id();
            $table->string('no_bapb',100)->nullable();
            $table->datetime('tgl_bapb')->nullable();
            $table->string('leveransir', 255)->nullable();
            $table->string('no_surat_jalan', 255)->nullable();
            $table->datetime('tgl_surat_jalan')->nullable();
            $table->string('no_polisi',255)->nullable();
            $table->string('jenis_kendaraan', 255)->nullable();
            $table->unsignedBigInteger('user_input')->nullable();
            $table->foreign('user_input')->references('id')->on('users');
            $table->unsignedBigInteger('user_update')->nullable();
            $table->foreign('user_update')->references('id')->on('users');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('bapb');
    }
}
