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
        Schema::create('device', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('nickname');
            $table->string('summary')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });

        Schema::create('figure', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('device_id')->constrained('device')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('key');
            $table->string('summary')->nullable();
            $table->string('chart')->nullable();
            $table->string('location')->nullable();
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->boolean('calibrate')->default(false);
            $table->decimal('calibrate_value', 8, 2)->nullable();
            $table->boolean('calibrate_percentage')->default(false);
        });

        Schema::create('datum', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('figure_id')->nullable()->constrained('figure')->setNullOnDelete()->cascadeOnUpdate();
            $table->float('value', 16, 4);
            $table->datetime('timestamp');
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
        Schema::dropIfExists('datum');
        Schema::dropIfExists('figure');
        Schema::dropIfExists('device');
    }
};
