<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Gives subjects their own icon and replaces the figure's small badge icon.
 *
 * The card shows the subject's icon with the figure's icon as a small badge. To keep existing
 * cards looking the same, each figure's main icon moves to its current subject and its badge
 * becomes the figure's own icon. Figures with no current subject keep their main icon.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subject', function (Blueprint $table) {
            $table->string('icon')->nullable();
            $table->string('icon_custom')->nullable();
        });

        $assignments = DB::table('figure_assignment')->whereNull('ended_at')->pluck('subject_id', 'figure_id');

        foreach (DB::table('figure')->get() as $figure) {
            $subjectId = $assignments[$figure->id] ?? null;
            if ($subjectId === null) {
                continue;
            }

            DB::table('subject')->where('id', $subjectId)->update(['icon' => $figure->icon, 'icon_custom' => $figure->icon_custom]);
            DB::table('figure')->where('id', $figure->id)->update(['icon' => $figure->icon_small, 'icon_custom' => null]);
        }

        Schema::table('figure', function (Blueprint $table) {
            $table->dropColumn('icon_small');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('figure', function (Blueprint $table) {
            $table->string('icon_small')->nullable();
        });

        $assignments = DB::table('figure_assignment')->whereNull('ended_at')->pluck('subject_id', 'figure_id');
        $subjects = DB::table('subject')->get()->keyBy('id');

        foreach (DB::table('figure')->get() as $figure) {
            $subject = $subjects[$assignments[$figure->id] ?? null] ?? null;
            if ($subject === null) {
                continue;
            }

            DB::table('figure')->where('id', $figure->id)->update([
                'icon' => $subject->icon,
                'icon_custom' => $subject->icon_custom,
                'icon_small' => $figure->icon,
            ]);
        }

        Schema::table('subject', function (Blueprint $table) {
            $table->dropColumn(['icon', 'icon_custom']);
        });
    }
};
