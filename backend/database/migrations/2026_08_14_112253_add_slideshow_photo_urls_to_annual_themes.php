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
        Schema::table('annual_themes', function (Blueprint $table) {
            $table->json('slideshow_photo_urls')->nullable()->after('photo_url');
        });
    }

    public function down(): void
    {
        Schema::table('annual_themes', function (Blueprint $table) {
            $table->dropColumn('slideshow_photo_urls');
        });
    }
};
