<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Splits "what is being measured" (a Subject, e.g. a plant) from the sensor channel (a Figure).
 *
 * A Figure is assigned to a Subject for a period of time via FigureAssignment. Readings stay
 * attached to the Figure; which Subject a reading belongs to is derived from its timestamp.
 * Alerts move from the Figure to the Subject.
 *
 * Existing data is migrated by creating one Subject per Figure (named after it), an open
 * assignment starting at the Figure's first reading, and moving its alerts to that Subject.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subject', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('summary')->nullable();
            $table->string('image')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('figure_assignment', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('figure_id')->constrained('figure')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUlid('subject_id')->constrained('subject')->cascadeOnDelete()->cascadeOnUpdate();
            $table->datetime('started_at')->comment('When the subject started being measured by the figure.');
            $table->datetime('ended_at')->nullable()->comment('When the assignment ended. Null while it is current.');
            $table->timestamps();
            $table->index(['figure_id', 'started_at']);
            $table->index(['subject_id', 'started_at']);
        });

        Schema::create('subject_alert', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('subject_id')->constrained('subject')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('enabled')->default(false)->comment('Enables alert.');
            $table->float('value', 16)->nullable()->comment('Setting a value here enables alert.');
            $table->boolean('lower')->default(false)->comment('Alert on readings lower than "value"');
            $table->boolean('email')->default(false)->comment('Send email notification');
            $table->datetime('activated')->nullable()->comment('Last time alert was raised.');
        });

        $now = now();
        foreach (DB::table('figure')->orderBy('order')->get() as $figure) {
            $subjectId = (string) Str::ulid();
            DB::table('subject')->insert([
                'id' => $subjectId,
                'name' => $figure->name,
                'summary' => $figure->summary,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            DB::table('figure_assignment')->insert([
                'id' => (string) Str::ulid(),
                'figure_id' => $figure->id,
                'subject_id' => $subjectId,
                'started_at' => DB::table('datum')->where('figure_id', $figure->id)->min('timestamp') ?? $now,
                'ended_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            foreach (DB::table('figure_alert')->where('figure_id', $figure->id)->get() as $alert) {
                DB::table('subject_alert')->insert([
                    'id' => $alert->id,
                    'subject_id' => $subjectId,
                    'enabled' => $alert->enabled,
                    'value' => $alert->value,
                    'lower' => $alert->lower,
                    'email' => $alert->email,
                    'activated' => $alert->activated,
                ]);
            }
        }

        Schema::dropIfExists('figure_alert');
    }

    /**
     * Reverse the migrations.
     *
     * Alerts are moved back to the figure each subject was most recently assigned to;
     * alerts on subjects that were never assigned are lost.
     */
    public function down(): void
    {
        Schema::create('figure_alert', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('figure_id')->constrained('figure')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('enabled')->default(false)->comment('Enables alert.');
            $table->float('value', 16)->nullable()->comment('Setting a value here enables alert.');
            $table->boolean('lower')->default(false)->comment('Alert on readings lower than "value"');
            $table->boolean('email')->default(false)->comment('Send email notification');
            $table->datetime('activated')->nullable()->comment('Last time alert was raised.');
        });

        foreach (DB::table('subject_alert')->get() as $alert) {
            $figureId = DB::table('figure_assignment')
                ->where('subject_id', $alert->subject_id)
                ->orderByDesc('started_at')
                ->value('figure_id');
            if ($figureId) {
                DB::table('figure_alert')->insert([
                    'id' => $alert->id,
                    'figure_id' => $figureId,
                    'enabled' => $alert->enabled,
                    'value' => $alert->value,
                    'lower' => $alert->lower,
                    'email' => $alert->email,
                    'activated' => $alert->activated,
                ]);
            }
        }

        Schema::dropIfExists('subject_alert');
        Schema::dropIfExists('figure_assignment');
        Schema::dropIfExists('subject');
    }
};
