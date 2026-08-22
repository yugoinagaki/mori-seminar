<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('cohort_id')
                ->nullable()
                ->after('name')
                ->constrained('cohorts')
                ->nullOnDelete();

            $table->string('position')->nullable()->after('cohort_id')->comment('役職');
        });

        $generations = DB::table('members')
            ->whereNotNull('generation')
            ->pluck('generation')
            ->unique()
            ->values();

        foreach ($generations as $generation) {
            $cohortId = DB::table('cohorts')->where('generation', $generation)->value('id');
            if (!$cohortId) {
                $cohortId = DB::table('cohorts')->insertGetId([
                    'generation' => $generation,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('members')
                ->where('generation', $generation)
                ->update(['cohort_id' => $cohortId]);
        }
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cohort_id');
            $table->dropColumn('position');
        });
    }
};
