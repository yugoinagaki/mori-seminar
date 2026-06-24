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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_kana')->nullable();
            $table->unsignedSmallInteger('generation')->nullable()->comment('何期生か');
            $table->unsignedTinyInteger('university_year')->nullable()->comment('学年 1〜4');
            $table->string('major')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_image_url')->nullable();
            $table->enum('status', ['active', 'ob_og'])->default('active');
            $table->unsignedSmallInteger('graduated_year')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->unsignedSmallInteger('order_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
