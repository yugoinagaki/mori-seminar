<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('contact_email')->nullable()->after('page_visibilities');
            $table->string('contact_twitter_url')->nullable()->after('contact_email');
            $table->string('contact_instagram_url')->nullable()->after('contact_twitter_url');
        });

        // Seed with current hardcoded values so nothing changes visually on deploy
        DB::table('site_settings')->where('id', 1)->update([
            'contact_email'         => 'morisemi2020@gmail.com',
            'contact_twitter_url'   => 'https://twitter.com/morisemi_keio',
            'contact_instagram_url' => 'https://instagram.com/keio.mori',
        ]);
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['contact_email', 'contact_twitter_url', 'contact_instagram_url']);
        });
    }
};
