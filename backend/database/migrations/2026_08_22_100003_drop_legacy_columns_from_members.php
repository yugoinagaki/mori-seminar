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
            $table->dropColumn([
                'generation',
                'name_kana',
                'university_year',
                'major',
                'status',
                'graduated_year',
                'twitter_url',
                'instagram_url',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('name_kana')->nullable()->after('name');
            $table->unsignedSmallInteger('generation')->nullable()->after('name_kana')->comment('何期生か');
            $table->unsignedTinyInteger('university_year')->nullable()->comment('学年 1〜4');
            $table->string('major')->nullable();
            $table->enum('status', ['active', 'ob_og'])->default('active');
            $table->unsignedSmallInteger('graduated_year')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
        });

        DB::table('members')
            ->whereNotNull('cohort_id')
            ->orderBy('id')
            ->chunkById(100, function ($members) {
                foreach ($members as $member) {
                    $generation = DB::table('cohorts')->where('id', $member->cohort_id)->value('generation');
                    if ($generation !== null) {
                        DB::table('members')->where('id', $member->id)->update(['generation' => $generation]);
                    }
                }
            });
    }
};
