<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professor', function (Blueprint $table) {
            $table->text('research_themes_body')->nullable()->after('research_themes')
                  ->comment('研究テーマ本文 (HTML)。研究テーマ配列を段階的に置き換える');
            $table->string('achievements_pdf_url')->nullable()->after('gallery_photo_urls')
                  ->comment('業績一覧PDFのファイルパス');
            $table->string('achievements_pdf_note')->nullable()->after('achievements_pdf_url')
                  ->comment('例: 2026年4月15日現在');
            $table->json('bio_blocks')->nullable()->after('bio')
                  ->comment('見出し+本文のブロック繰り返し。bioより優先');
        });

        // Convert legacy research_themes (array of strings) → bullet-form text
        $rows = DB::table('professor')->get(['id', 'research_themes']);
        foreach ($rows as $row) {
            if (!$row->research_themes) continue;
            $items = json_decode($row->research_themes, true);
            if (!is_array($items) || empty($items)) continue;
            $body = collect($items)
                ->filter(fn ($v) => is_string($v) && trim($v) !== '')
                ->map(fn ($v) => '・' . trim($v))
                ->implode("\n");
            if ($body !== '') {
                DB::table('professor')->where('id', $row->id)->update([
                    'research_themes_body' => $body,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('professor', function (Blueprint $table) {
            $table->dropColumn(['research_themes_body', 'achievements_pdf_url', 'achievements_pdf_note', 'bio_blocks']);
        });
    }
};
