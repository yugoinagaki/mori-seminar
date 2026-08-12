<?php

namespace App\Http\Controllers;

use App\Models\AnnualTheme;
use App\Models\Member;
use App\Models\Post;
use App\Models\Professor;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $posts    = Post::with(['author:id,name', 'tags:id,name,slug'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $tickerPosts = Post::where('status', 'published')
            ->where('type', 'news')
            ->orderBy('published_at', 'desc')
            ->take(20)
            ->get();

        $members  = Member::where('status', 'active')
            ->orderBy('order_index')
            ->orderBy('generation', 'desc')
            ->take(6)
            ->get();

        $settings  = SiteSetting::instance();
        $professor = Professor::first();
        $theme     = AnnualTheme::orderBy('year', 'desc')->first();
        $worldNews = $this->fetchWorldNews();

        return view('home.index', compact(
            'posts', 'tickerPosts', 'members',
            'settings', 'professor', 'theme', 'worldNews'
        ));
    }

    private function fetchWorldNews(): array
    {
        return Cache::remember('world-news', 60 * 30, function () {
            try {
                $response = Http::timeout(10)->get('https://www.nhk.or.jp/rss/news/cat6.xml');
                if (! $response->successful()) return [];

                $xml     = $response->body();
                $results = [];

                preg_match_all('/<item>([\s\S]*?)<\/item>/i', $xml, $itemMatches);
                foreach ($itemMatches[1] as $block) {
                    $title = null;
                    if (preg_match('/<title><!\[CDATA\[([\s\S]*?)\]\]><\/title>/i', $block, $m)) {
                        $title = trim($m[1]);
                    } elseif (preg_match('/<title>([\s\S]*?)<\/title>/i', $block, $m)) {
                        $title = trim($m[1]);
                    }

                    $link = null;
                    if (preg_match('/<link>([\s\S]*?)<\/link>/i', $block, $m)) {
                        $link = trim($m[1]);
                    } elseif (preg_match('/<guid>([\s\S]*?)<\/guid>/i', $block, $m)) {
                        $link = trim($m[1]);
                    }

                    $pubDate = '';
                    if (preg_match('/<pubDate>([\s\S]*?)<\/pubDate>/i', $block, $m)) {
                        $pubDate = trim($m[1]);
                    }

                    if ($title && $link) {
                        $results[] = ['title' => $title, 'link' => $link, 'pubDate' => $pubDate];
                    }

                    if (count($results) >= 8) break;
                }

                return $results;
            } catch (\Throwable) {
                return [];
            }
        });
    }
}
