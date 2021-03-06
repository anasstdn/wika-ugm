<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiwayatPenerimaanBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_penerimaan_barang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('riwayat_stok_id')->nullable();
            $table->foreign('riwayat_stok_id')->references('id')->on('riwayat_stok');
            $table->unsignedBigInteger('detail_bapb_id')->nullable();
            $table->foreign('detail_bapb_id')->references('id')->on('detail_bapb');
            $table->unsignedBigInteger('detail_po_id')->nullable();
            $table->foreign('detail_po_id')->references('id')->on('detail_po');
            $table->unsignedBigInteger('user_input')->nullable();
            $table->foreign('user_input')->references('id')->on('users');
            $table->unsignedBigInteger('user_update')->nullable();
            $table->foreign('user_update')->references('id')->on('users');
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
        Schema::dropIfExists('riwayat_penerimaan_barang');
    }
}
