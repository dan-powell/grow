<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('device_id')->nullable()->constrained('device')->nullOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('severity')->nullable();
            $table->string('icon')->nullable();
            $table->string('summary')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log');
    }
};
