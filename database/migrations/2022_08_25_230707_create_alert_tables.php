<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device', function (Blueprint $table) {
            $table->boolean('alert_enabled')->default(false)->comment('Enables alerts.');
            $table->integer('alert_timeout')->nullable()->comment('Time since last reading before raising alert.');
            $table->boolean('alert_email')->default(false)->comment('Send email notification');
            $table->datetime('alert_activated')->nullable()->comment('Last time alert was raised.');
        });

        Schema::create('figure_alert', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('figure_id')->constrained('figure')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('enabled')->default(false)->comment('Enables alert.');
            $table->float('value', 16, 4)->nullable()->comment('Setting a value here enables alert.');
            $table->boolean('lower')->default(false)->comment('Alert on readings lower than "value"');
            $table->boolean('email')->default(false)->comment('Send email notification');
            $table->datetime('activated')->nullable()->comment('Last time alert was raised.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('figure_alert');
        Schema::table('device', function (Blueprint $table) {
            $table->dropColumn('alert_enabled');
            $table->dropColumn('alert_timeout');
            $table->dropColumn('alert_active');
        });
    }
};
