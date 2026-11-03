<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('device', function (Blueprint $table) {
            $table->boolean('enabled')->default(true)->comment('Whether the device and its figures are shown on the site.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device', function (Blueprint $table) {
            $table->dropColumn('enabled');
        });
    }
};
