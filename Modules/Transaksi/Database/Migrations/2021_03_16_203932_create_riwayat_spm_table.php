<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiwayatSpmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_spm', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spm_id')->nullable();
            $table->foreign('spm_id')->references('id')->on('spm');
            $table->unsignedBigInteger('action_id')->nullable();
            $table->foreign('action_id')->references('id')->on('action');
            $table->unsignedBigInteger('user_input')->nullable();
            $table->foreign('user_input')->references('id')->on('users');
            $table->datetime('datetime_log')->nullable();
            $table->json('description')->nullable();
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
        Schema::dropIfExists('riwayat_spm');
    }
}
