<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Models\Member;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InlineCreateController extends Controller
{
    public function post(Request $request)
    {
        $validated = $request->validate([
            'type'         => ['required', 'in:news,activity,admission,blog'],
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['nullable', 'string'],
            'excerpt'      => ['nullable', 'string', 'max:500'],
            'published_at' => ['nullable', 'date'],
        ]);

        $post = Post::create([
            'type'         => $validated['type'],
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']) . '-' . Str::random(4),
            'content'      => $validated['content'] ?? '',
            'excerpt'      => $validated['excerpt'] ?? null,
            'author_id'    => auth()->id(),
            'status'       => 'published',
            'published_at' => $validated['published_at'] ?? now(),
        ]);

        $base = in_array($post->type, ['blog']) ? 'blog' : 'news';

        return redirect("/{$base}/{$post->slug}");
    }

    public function caseStudy(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'content'      => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);

        $cs = CaseStudy::create([
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']) . '-' . Str::random(4),
            'description'  => $validated['description'] ?? null,
            'content'      => $validated['content'] ?? '',
            'status'       => 'published',
            'published_at' => $validated['published_at'] ?? now(),
        ]);

        return redirect("/case-studies/{$cs->slug}");
    }

    public function member(Request $request)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'name_kana'       => ['nullable', 'string', 'max:100'],
            'generation'      => ['nullable', 'integer'],
            'university_year' => ['nullable', 'string', 'max:10'],
            'major'           => ['nullable', 'string', 'max:200'],
            'bio'             => ['nullable', 'string'],
            'status'          => ['required', 'in:active,alumni'],
        ]);

        Member::create($validated);

        return redirect('/members');
    }
}
