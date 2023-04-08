<?php

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
            $table->integer('order')->nullable()->comment('Display sorting order.');
        });

        Schema::table('figure', function (Blueprint $table) {
            $table->integer('order')->nullable()->comment('Display sorting order.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('figure', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('device', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
