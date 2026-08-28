<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('annual_themes', function (Blueprint $table) {
            $table->enum('semester', ['spring', 'fall'])->nullable()->after('year')->comment('春学期/秋学期。null=通年');
        });
    }

    public function down(): void
    {
        Schema::table('annual_themes', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }
};
