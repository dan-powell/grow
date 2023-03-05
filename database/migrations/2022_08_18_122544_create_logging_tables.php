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

        Schema::create('device_config', function (Blueprint $table) {
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

        Schema::create('reading', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('device_id')->constrained('device')->cascadeOnDelete()->cascadeOnUpdate();
            $table->datetime('timestamp');
            $table->timestamps();
        });

        Schema::create('reading_data', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('reading_id')->constrained('reading')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUlid('config_id')->nullable()->constrained('device_config')->setNullOnDelete()->cascadeOnUpdate();
            $table->string('key');
            $table->float('value', 16, 4);
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
        Schema::dropIfExists('device_config');
        Schema::dropIfExists('device');
    }
};
