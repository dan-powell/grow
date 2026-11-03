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
        Schema::table('subject_alert', function (Blueprint $table) {
            $table->float('resolve_margin', 16)->default(0)->after('lower')->comment('How far back past "value" a reading must come before the alert resolves.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_alert', function (Blueprint $table) {
            $table->dropColumn('resolve_margin');
        });
    }
};
