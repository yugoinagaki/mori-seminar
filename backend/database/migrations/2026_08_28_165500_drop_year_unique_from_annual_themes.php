<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('annual_themes', function (Blueprint $table) {
            $table->dropUnique('annual_themes_year_unique');
        });
    }

    public function down(): void
    {
        Schema::table('annual_themes', function (Blueprint $table) {
            $table->unique('year', 'annual_themes_year_unique');
        });
    }
};
