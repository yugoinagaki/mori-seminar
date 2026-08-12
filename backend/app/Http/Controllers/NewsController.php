<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    private const TYPE_OPTIONS = [
        'all'       => 'すべて',
        'news'      => 'NEWS',
        'activity'  => '活動報告',
        'admission' => '入ゼミ',
    ];

    public function index(Request $request)
    {
        $activeType = $request->get('type', 'all');
        if (! array_key_exists($activeType, self::TYPE_OPTIONS)) {
            $activeType = 'all';
        }

        $query = Post::with(['author:id,name', 'tags:id,name,slug'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        if ($activeType === 'all') {
            $query->whereIn('type', ['news', 'activity', 'admission']);
        } else {
            $query->where('type', $activeType);
        }

        $posts      = $query->paginate(20)->withQueryString();
        $typeOptions = self::TYPE_OPTIONS;

        return view('news.index', compact('posts', 'activeType', 'typeOptions'));
    }

    public function show(string $slug)
    {
        $post = Post::with(['author:id,name', 'tags:id,name,slug'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('news.show', compact('post'));
    }
}
