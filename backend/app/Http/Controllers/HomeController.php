<?php

namespace App\Http\Controllers;

use App\Models\AnnualTheme;
use App\Models\Post;
use App\Models\Professor;
use App\Models\SiteSetting;
use App\Models\ThemeYear;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $setting   = SiteSetting::instance();
        $theme     = AnnualTheme::orderBy('year', 'desc')->orderedByRecency()->first();
        $themeYear = $theme ? ThemeYear::where('year', $theme->year)->first() : null;
        $professor = \App\Models\Professor::first();
        $recentPosts = Post::with(['author:id,name', 'tags:id,name,slug'])
            ->where('status', 'published')
            ->whereIn('type', ['news', 'activity', 'admission', 'blog'])
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        $cached = Cache::get('world_news');
        $worldNews = $cached ?: [];

        if (empty($cached)) {
            try {
                $ctx  = stream_context_create(['http' => ['timeout' => 5]]);
                $body = @file_get_contents('https://news.web.nhk/n-data/conf/na/rss/cat6.xml', false, $ctx);
                if ($body !== false) {
                    $xml = simplexml_load_string($body);
                    if ($xml) {
                        $items = [];
                        foreach ($xml->channel->item as $item) {
                            $items[] = [
                                'title'   => (string) $item->title,
                                'link'    => (string) $item->link,
                                'pubDate' => (string) $item->pubDate,
                            ];
                            if (count($items) >= 10) break;
                        }
                        if ($items) {
                            Cache::put('world_news', $items, 1800);
                            $worldNews = $items;
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Log::error('NHK RSS fetch failed: ' . $e->getMessage());
            }
        }

        return view('home.index', compact('setting', 'theme', 'themeYear', 'professor', 'recentPosts', 'worldNews'));
    }
}
