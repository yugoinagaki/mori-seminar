<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::with(['author:id,name', 'tags:id,name,slug'])
            ->where('status', 'published')
            ->where('type', 'blog')
            ->orderBy('published_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('blog.index', ['posts' => $posts, 'showWipe' => true]);
    }

    public function show(string $slug)
    {
        $post = Post::with(['author:id,name', 'tags:id,name,slug'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('type', 'blog')
            ->firstOrFail();

        return view('blog.show', ['post' => $post, 'showWipe' => true]);
    }
}
