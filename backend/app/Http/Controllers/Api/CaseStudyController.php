<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use Illuminate\Http\Request;

class CaseStudyController extends Controller
{
    public function index()
    {
        return response()->json(
            CaseStudy::with('author:id,name')
                ->where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->paginate(12)
        );
    }

    public function show(string $slug)
    {
        $cs = CaseStudy::with('author:id,name')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json($cs);
    }
}
