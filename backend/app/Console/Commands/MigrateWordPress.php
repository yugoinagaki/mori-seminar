<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateWordPress extends Command
{
    protected $signature = 'migrate:wordpress
        {--since=2022-01-01 : この日付以降の投稿のみ対象}
        {--dry-run          : DBに書き込まず結果のみ表示}
        {--include-drafts   : 下書きも対象に含める}
        {--limit=0          : 処理件数の上限（0=無制限）}';

    protected $description = '旧WordPressサイトの記事を新Laravelサイトへ移行する';

    // タイトル接頭辞 → type のマッピング（上から順に評価）
    // 【】付きルールを先に評価し、「森先生」のような部分一致より優先させる
    private const TYPE_RULES = [
        '【NEWS】'              => 'news',
        '【テキスト刊行】'        => 'activity',
        '【活動報告】'           => 'activity',
        '【レク企画】'           => 'activity',
        '【入ゼミ関連'           => 'admission',
        '【1年生の皆さんへ】'     => 'admission',
        '【ゼミ生の日常】'        => 'blog',
        '期生が森ゼミを選んだ理由】' => 'blog',  // 【N期生が森ゼミを...
        '期生ブログ'             => 'blog',       // 【N期生ブログ vol.X】
        '期生の日常】'           => 'blog',
        // 括弧なしパターンは後に評価（部分一致なので他ルールより後ろ）
        '森先生'               => 'news',
        '森聡先生'              => 'news',
    ];

    // 旧サイト画像URLの接頭辞パターン（http/https/プロトコル相対）
    private const WP_UPLOAD_PATTERNS = [
        'https://xs702382.xsrv.jp/morisemi/wp-content/uploads/',
        'http://xs702382.xsrv.jp/morisemi/wp-content/uploads/',
        '//xs702382.xsrv.jp/morisemi/wp-content/uploads/',
    ];

    private const NEW_UPLOAD_PREFIX = '/storage/legacy/';

    private bool $dryRun = false;
    private array $brokenImages = [];
    private array $typeCount = ['news' => 0, 'blog' => 0, 'activity' => 0, 'admission' => 0];
    private int $inserted = 0;
    private int $updated = 0;
    private int $skipped = 0;

    public function handle(): int
    {
        $this->dryRun = (bool) $this->option('dry-run');
        $since        = $this->option('since') ?: '2022-01-01';
        $limit        = (int) $this->option('limit');

        if ($this->dryRun) {
            $this->warn('=== DRY RUN MODE — DBへの書き込みは行いません ===');
        }

        // 移行用ユーザーを取得（super_admin優先、なければ最初のユーザー）
        $migrationUser = DB::table('users')
            ->where('role', 'super_admin')
            ->orderBy('id')
            ->first();
        if (! $migrationUser) {
            $migrationUser = DB::table('users')->orderBy('id')->first();
        }
        if (! $migrationUser) {
            $this->error('usersテーブルにレコードがありません。先にFilamentで管理者ユーザーを作成してください。');
            return Command::FAILURE;
        }
        $authorId = $migrationUser->id;
        $this->line("投稿者: {$migrationUser->name} (id={$authorId})");

        // WordPressから投稿を取得
        try {
            $statuses = ['publish'];
            if ($this->option('include-drafts')) {
                $statuses[] = 'draft';
            }

            $query = DB::connection('wordpress')
                ->table('wp_posts')
                ->where('post_type', 'post')
                ->whereIn('post_status', $statuses)
                ->where('post_date', '>=', $since)
                ->orderBy('post_date');

            if ($limit > 0) {
                $query->limit($limit);
            }

            $wpPosts = $query->get();
        } catch (\Throwable $e) {
            $this->error('WordPressDBへの接続に失敗しました: ' . $e->getMessage());
            $this->line('サーバー上の .env に WP_DB_* が設定されているか確認してください。');
            return Command::FAILURE;
        }

        $this->info("取得: {$wpPosts->count()} 件 (since={$since})");
        $this->newLine();

        foreach ($wpPosts as $wp) {
            $this->processPost($wp, $authorId);
        }

        $this->printReport();

        return Command::SUCCESS;
    }

    private function processPost(object $wp, int $authorId): void
    {
        $title   = $wp->post_title;
        $type    = $this->classifyType($title);
        $content = $this->cleanContent($wp->post_content, $title);
        $slug    = $this->resolveSlug($wp->ID, $wp->post_name);
        $thumb   = $this->resolveThumbnail($wp->ID, $content);
        $excerpt = $this->resolveExcerpt($wp->post_excerpt, $content);
        $status  = $wp->post_status === 'publish' ? 'published' : 'draft';
        $pubAt   = ($wp->post_status === 'publish' && $wp->post_date !== '0000-00-00 00:00:00')
            ? $wp->post_date
            : null;

        $this->typeCount[$type]++;

        // リンク切れ検査
        $this->checkBrokenImages($content, $title);

        if ($this->dryRun) {
            $this->line(sprintf(
                "[%s] [%s] %s\n  slug=%s, thumb=%s",
                $type,
                $status,
                $title,
                $slug,
                $thumb ?? '(なし)',
            ));
            return;
        }

        // タグ処理
        $tags = $this->resolveWpTags($wp->ID);

        // Upsert
        $existing = DB::table('posts')->where('wp_id', $wp->ID)->first();

        $data = [
            'title'        => $title,
            'slug'         => $slug,
            'content'      => $content,
            'excerpt'      => $excerpt,
            'type'         => $type,
            'author_id'    => $authorId,
            'thumbnail_url'=> $thumb,
            'status'       => $status,
            'published_at' => $pubAt,
            'updated_at'   => now(),
        ];

        if ($existing) {
            DB::table('posts')->where('wp_id', $wp->ID)->update($data);
            $postId = $existing->id;
            $this->updated++;
        } else {
            $data['wp_id']     = $wp->ID;
            $data['created_at'] = $wp->post_date !== '0000-00-00 00:00:00' ? $wp->post_date : now();
            $postId = DB::table('posts')->insertGetId($data);
            $this->inserted++;
        }

        // タグを同期
        $this->syncTags($postId, $tags);
    }

    private function classifyType(string $title): string
    {
        foreach (self::TYPE_RULES as $prefix => $type) {
            if (str_contains($title, $prefix)) {
                return $type;
            }
        }
        return 'news';
    }

    private function cleanContent(string $content, string $title): string
    {
        // 0. <style>/<script>ブロックを中身ごと除去（ElementorのインラインCSS等）
        $content = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $content);
        $content = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $content);

        // 1. 入れ子見出しを修復: <h2><h2 ...>text</h2></h2> → <h2>text</h2>
        $content = preg_replace_callback(
            '/<(h[1-6])>(<\1[^>]*>)(.*?)<\/\1><\/\1>/is',
            fn ($m) => "<{$m[1]}>{$m[3]}</{$m[1]}>",
            $content
        );

        // 2. Elementor残骸属性を除去
        $content = preg_replace('/\s+data-elementor-setting-key="[^"]*"/', '', $content);
        $content = preg_replace('/\s+data-pen-placeholder="[^"]*"/', '', $content);

        // 3. 画像URLの接頭辞置換（srcset内の複数URLも処理）
        foreach (self::WP_UPLOAD_PATTERNS as $old) {
            $content = str_replace($old, self::NEW_UPLOAD_PREFIX, $content);
        }

        // 4. 冒頭のタイトル重複除去（タイトルと前方一致する見出し要素を削除）
        $titleBase = trim(preg_replace('/\s*\([\d年月日\s]+\)\s*$/', '', $title));
        if ($titleBase !== '') {
            $content = preg_replace_callback(
                '/<h[1-6][^>]*>\s*(.*?)\s*<\/h[1-6]>/is',
                function ($m) use ($titleBase, &$removed) {
                    $text = strip_tags($m[1]);
                    if (! isset($removed) && str_starts_with($text, $titleBase)) {
                        $removed = true;
                        return '';
                    }
                    return $m[0];
                },
                $content,
                1
            );
        }

        return trim($content);
    }

    private function resolveSlug(int $wpId, string $wpPostName): string
    {
        $decoded = urldecode($wpPostName);
        // 英数字・ハイフン・アンダースコアのみならそのまま流用
        if ($decoded && preg_match('/^[A-Za-z0-9_-]+$/', $decoded)) {
            $slug = $decoded;
        } else {
            $slug = 'post-' . $wpId;
        }

        // 衝突回避（既存の別レコードと被る場合は連番を付ける）
        $base      = $slug;
        $collision = DB::table('posts')
            ->where('slug', $slug)
            ->whereNull('wp_id')  // 新サイト由来のレコードとのみ衝突チェック
            ->exists();
        if ($collision) {
            $i = 2;
            while (DB::table('posts')->where('slug', "{$base}-{$i}")->exists()) {
                $i++;
            }
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }

    private function resolveThumbnail(int $wpId, string $content): ?string
    {
        // _thumbnail_id → wp_posts.guid から画像URLを取得
        $meta = DB::connection('wordpress')
            ->table('wp_postmeta')
            ->where('post_id', $wpId)
            ->where('meta_key', '_thumbnail_id')
            ->value('meta_value');

        if ($meta) {
            $guid = DB::connection('wordpress')
                ->table('wp_posts')
                ->where('ID', $meta)
                ->value('guid');
            if ($guid) {
                return $this->toThumbPath($this->replaceUploadUrl($guid));
            }
        }

        // フォールバック: 本文中の最初の <img src="...">
        // content は cleanContent() 後なので src は /storage/legacy/... 形式
        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $content, $m)) {
            return $this->toThumbPath($m[1]);
        }

        return null;
    }

    // storage_url() が 'storage/' を自動付与するため、先頭の '/storage/' を除去する
    private function toThumbPath(string $url): string
    {
        if (str_starts_with($url, '/storage/')) {
            return substr($url, strlen('/storage/'));
        }
        return $url;
    }

    private function resolveExcerpt(string $wpExcerpt, string $content): ?string
    {
        if (trim($wpExcerpt) !== '') {
            return html_entity_decode(trim($wpExcerpt), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        $text = strip_tags($content);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        return Str::limit(trim($text), 200) ?: null;
    }

    private function replaceUploadUrl(string $url): string
    {
        foreach (self::WP_UPLOAD_PATTERNS as $old) {
            if (str_starts_with($url, $old)) {
                return self::NEW_UPLOAD_PREFIX . substr($url, strlen($old));
            }
        }
        return $url;
    }

    private function checkBrokenImages(string $content, string $title): void
    {
        preg_match_all('|/storage/legacy/([^\s"\'<>]+)|', $content, $matches);
        foreach ($matches[1] as $path) {
            $fullPath = storage_path('app/public/legacy/' . $path);
            if (! file_exists($fullPath)) {
                $this->brokenImages[] = ['post' => $title, 'path' => '/storage/legacy/' . $path];
            }
        }
    }

    private function resolveWpTags(int $wpId): array
    {
        $rows = DB::connection('wordpress')
            ->table('wp_term_relationships as tr')
            ->join('wp_term_taxonomy as tt', 'tr.term_taxonomy_id', '=', 'tt.term_taxonomy_id')
            ->join('wp_terms as t', 'tt.term_id', '=', 't.term_id')
            ->where('tr.object_id', $wpId)
            ->where('tt.taxonomy', 'post_tag')
            ->pluck('t.name')
            ->toArray();

        // 年号だけのタグを除外
        return array_filter($rows, fn ($name) => ! preg_match('/^\d{4}$/', $name));
    }

    private function syncTags(int $postId, array $tagNames): void
    {
        DB::table('post_tag')->where('post_id', $postId)->delete();

        foreach ($tagNames as $name) {
            $name = trim($name);
            if ($name === '') continue;

            $slug = Str::slug($name) ?: Str::slug(rawurlencode($name));

            $tag = DB::table('tags')->where('name', $name)->first();
            if (! $tag) {
                // slug衝突回避
                $baseSlug = $slug;
                $i = 2;
                while (DB::table('tags')->where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$i}";
                    $i++;
                }
                $tagId = DB::table('tags')->insertGetId([
                    'name'       => $name,
                    'slug'       => $slug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $tagId = $tag->id;
            }

            DB::table('post_tag')->insertOrIgnore([
                'post_id' => $postId,
                'tag_id'  => $tagId,
            ]);
        }
    }

    private function printReport(): void
    {
        $this->newLine();
        $this->info('=== 移行結果 ===');

        if (! $this->dryRun) {
            $this->line("  新規: {$this->inserted} 件");
            $this->line("  更新: {$this->updated} 件");
        }

        $this->newLine();
        $this->info('type別内訳:');
        foreach ($this->typeCount as $type => $count) {
            $this->line("  {$type}: {$count} 件");
        }

        if (! empty($this->brokenImages)) {
            $this->newLine();
            $this->warn('=== リンク切れ画像 (' . count($this->brokenImages) . ' 件) ===');
            foreach ($this->brokenImages as $item) {
                $this->line("  [{$item['post']}] {$item['path']}");
            }
        } else {
            $this->newLine();
            $this->info('リンク切れ画像: なし');
        }
    }
}
