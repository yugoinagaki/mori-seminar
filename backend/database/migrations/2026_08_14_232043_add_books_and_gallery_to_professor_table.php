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
        Schema::table('professor', function (Blueprint $table) {
            $table->json('books')->nullable()->after('research_themes');
            $table->json('gallery_photo_urls')->nullable()->after('books');
        });
    }

    public function down(): void
    {
        Schema::table('professor', function (Blueprint $table) {
            $table->dropColumn(['books', 'gallery_photo_urls']);
        });
    }
};
