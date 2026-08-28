<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('annual_themes', function (Blueprint $table) {
            $table->dropColumn(['photo_url', 'slideshow_photo_urls']);
        });
    }

    public function down(): void
    {
        Schema::table('annual_themes', function (Blueprint $table) {
            $table->string('photo_url')->nullable()->after('content');
            $table->json('slideshow_photo_urls')->nullable()->after('photo_url');
        });

        // Restore photos from theme_years (best effort)
        foreach (DB::table('theme_years')->get() as $ty) {
            DB::table('annual_themes')
                ->where('year', $ty->year)
                ->orderByRaw("CASE WHEN semester IS NULL THEN 0 WHEN semester = 'spring' THEN 1 ELSE 2 END")
                ->limit(1)
                ->update([
                    'photo_url'            => $ty->photo_url,
                    'slideshow_photo_urls' => $ty->slideshow_photo_urls,
                ]);
        }
    }
};
