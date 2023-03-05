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
        Schema::table('device', function (Blueprint $table) {
            $table->string('nickname')->nullable();
            $table->string('summary')->nullable();
            $table->string('location')->nullable();
        });

        Schema::create('device_dataconfig', function (Blueprint $table) {
            $table->id();
            $table->biginteger('device_id')->unsigned();
            $table->string('name');
            $table->string('key');
            $table->string('summary')->nullable();
            $table->string('location')->nullable();
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->boolean('calibrate')->default(false);
            $table->decimal('calibrate_value', 8, 2)->nullable();
            $table->boolean('calibrate_percentage')->default(false);
            $table->foreign('device_id')->references('id')->on('device')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_dataconfig');

        Schema::table('device', function (Blueprint $table) {
            $table->dropColumn('nickname');
            $table->dropColumn('summary');
            $table->dropColumn('location');
        });
    }
};
