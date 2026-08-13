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

        $worldNews = Cache::remember('world_news', 1800, function () {
            try {
                $rss = Http::timeout(5)->get('https://www3.nhk.or.jp/rss/news/cat6.xml');
                if (! $rss->successful()) return [];
                $xml = simplexml_load_string($rss->body());
                if (! $xml) return [];
                $items = [];
                foreach ($xml->channel->item as $item) {
                    $items[] = [
                        'title'   => (string) $item->title,
                        'link'    => (string) $item->link,
                        'pubDate' => (string) $item->pubDate,
                    ];
                    if (count($items) >= 10) break;
                }
                return $items;
            } catch (\Throwable) {
                return [];
            }
        });

        return view('home.index', compact('setting', 'theme', 'professor', 'recentPosts', 'worldNews'));
    }
}
