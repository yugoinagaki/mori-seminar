<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('annual_themes', function (Blueprint $table) {
            $table->string('photo_url')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('annual_themes', function (Blueprint $table) {
            $table->dropColumn('photo_url');
        });
    }
};
