<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        // Cohorts that have at least one published blog post
        $cohorts = Cohort::whereHas('posts', function ($q) {
                $q->where('status', 'published')->where('type', 'blog');
            })
            ->orderBy('generation', 'desc')
            ->get();

        $activeCohort = $request->query('cohort'); // 'all' | '{id}' | 'uncategorized' | null

        $posts = Post::with(['author:id,name', 'tags:id,name,slug', 'cohort'])
            ->where('status', 'published')
            ->where('type', 'blog')
            ->when($activeCohort && $activeCohort !== 'all', function ($q) use ($activeCohort) {
                if ($activeCohort === 'uncategorized') {
                    $q->whereNull('cohort_id');
                } else {
                    $q->where('cohort_id', $activeCohort);
                }
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $hasUncategorized = Post::where('status', 'published')
            ->where('type', 'blog')
            ->whereNull('cohort_id')
            ->exists();

        return view('blog.index', [
            'posts'            => $posts,
            'cohorts'          => $cohorts,
            'activeCohort'     => $activeCohort ?? 'all',
            'hasUncategorized' => $hasUncategorized,
            'showWipe'         => true,
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::with(['author:id,name', 'tags:id,name,slug'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('type', 'blog')
            ->firstOrFail();

        return view('blog.show', ['post' => $post]);
    }
}
