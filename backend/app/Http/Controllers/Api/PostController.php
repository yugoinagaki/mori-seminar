<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['author:id,name', 'categories:id,name,slug', 'tags:id,name,slug'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('types')) {
            $query->whereIn('type', explode(',', $request->types));
        }

        return $query->paginate((int) $request->get('per_page', 15));
    }

    public function show(string $slug)
    {
        $post = Post::with(['author:id,name', 'categories:id,name,slug', 'tags:id,name,slug'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json($post);
    }
}
