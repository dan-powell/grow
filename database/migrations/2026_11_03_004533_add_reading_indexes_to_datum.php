<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Charts filter a figure's readings by `timestamp`, and the latest-reading lookups
     * (and late-reading alerts) order them by `created_at`.
     */
    public function up(): void
    {
        Schema::table('datum', function (Blueprint $table) {
            $table->index(['figure_id', 'timestamp']);
            $table->index(['figure_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // MySQL drops the foreign key's own index once these cover it, so put it back first
        $restoreForeignKeyIndex = in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)
            && ! Schema::hasIndex('datum', ['figure_id']);

        Schema::table('datum', function (Blueprint $table) use ($restoreForeignKeyIndex) {
            if ($restoreForeignKeyIndex) {
                $table->index('figure_id', 'datum_figure_id_foreign');
            }
            $table->dropIndex(['figure_id', 'timestamp']);
            $table->dropIndex(['figure_id', 'created_at']);
        });
    }
};
