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
            $table->boolean('reading_alert')->default(false)->comment('Enables alerts.');
            $table->integer('reading_alert_time')->nullable()->comment('Time since last reading before raising alert.');
            $table->datetime('reading_alert_last')->nullable()->comment('Last time alert was raised.');
        });

        Schema::create('device_config_alert', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('config_id')->constrained('device_config')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('enabled')->default(false)->comment('Enables alert.');
            $table->float('value', 16, 4)->nullable()->comment('Setting a value here enables alert.');
            $table->boolean('lower')->default(false)->comment('Alert on readings lower than "value"');
            $table->datetime('alert_last')->nullable()->comment('Last time alert was raised');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_config_alert');
        Schema::dropIfExists('device');
    }
};