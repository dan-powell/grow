<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('reading', function (Blueprint $table) {
            $table->id();
            $table->biginteger('device_id')->unsigned();
            $table->datetime('timestamp');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('reading_data', function (Blueprint $table) {
            $table->id();
            $table->biginteger('reading_id')->unsigned();
            $table->string('name');
            $table->float('value');
            $table->foreign('reading_id')->references('id')->on('reading')->onDelete('cascade');
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
        Schema::dropIfExists('reading_data');
        Schema::dropIfExists('reading');
        Schema::dropIfExists('devices');
    }
};
