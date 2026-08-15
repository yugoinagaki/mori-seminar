<?php

namespace App\Http\Controllers;

use App\Models\AnnualTheme;
use App\Models\Post;
use App\Models\Professor;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $setting   = SiteSetting::instance();
        $theme     = AnnualTheme::orderBy('year', 'desc')->first();
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
                $rss = Http::timeout(5)
                    ->withOptions(['curl' => [CURLOPT_SSLVERSION => CURL_SSLVERSION_DEFAULT]])
                    ->get('https://news.web.nhk/n-data/conf/na/rss/cat6.xml');
                if ($rss->successful()) {
                    $xml = simplexml_load_string($rss->body());
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

        return view('home.index', compact('setting', 'theme', 'professor', 'recentPosts', 'worldNews'));
    }
}
