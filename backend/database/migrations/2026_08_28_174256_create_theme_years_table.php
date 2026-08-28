<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theme_years', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique();
            $table->string('photo_url')->nullable();
            $table->json('slideshow_photo_urls')->nullable();
            $table->timestamps();
        });

        // Aggregate existing photos to year level (prefer null semester, then spring, then fall)
        $years = DB::table('annual_themes')->distinct()->pluck('year');
        $orderExpr = "CASE WHEN semester IS NULL THEN 0 WHEN semester = 'spring' THEN 1 ELSE 2 END";

        foreach ($years as $year) {
            $photoRow = DB::table('annual_themes')
                ->where('year', $year)
                ->whereNotNull('photo_url')
                ->orderByRaw($orderExpr)
                ->first();

            $slideshowRow = DB::table('annual_themes')
                ->where('year', $year)
                ->whereNotNull('slideshow_photo_urls')
                ->orderByRaw($orderExpr)
                ->first();

            DB::table('theme_years')->insert([
                'year'                 => $year,
                'photo_url'            => $photoRow->photo_url ?? null,
                'slideshow_photo_urls' => $slideshowRow->slideshow_photo_urls ?? null,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_years');
    }
};
