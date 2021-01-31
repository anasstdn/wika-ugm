<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpmSurveiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spm_survei', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_spm_id')->nullable();
            $table->foreign('detail_spm_id')->references('id')->on('detail_spm');
            $table->unsignedBigInteger('detail_survei_id')->nullable();
            $table->foreign('detail_survei_id')->references('id')->on('detail_survei');
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
        Schema::dropIfExists('spm_survei');
    }
}
