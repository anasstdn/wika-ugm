<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_po', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_id')->nullable();
            $table->foreign('po_id')->references('id')->on('po');
            $table->unsignedBigInteger('material_id')->nullable();
            $table->foreign('material_id')->references('id')->on('material');
            $table->text('merek')->nullable();
            $table->double('volume',14,2)->nullable();
            $table->datetime('tgl_penggunaan')->nullable();
            $table->text('keterangan')->nullable();

            $table->double('harga_per_unit',14,2)->nullable();
            $table->double('subtotal',14,2)->nullable();

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
        Schema::dropIfExists('detail_po');
    }
}
