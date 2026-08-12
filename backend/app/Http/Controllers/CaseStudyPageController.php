<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;

class CaseStudyPageController extends Controller
{
    public function index()
    {
        $caseStudies = CaseStudy::with('author:id,name')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('case-studies.index', array_merge(compact('caseStudies'), ['showWipe' => true]));
    }

    public function show(string $slug)
    {
        $cs = CaseStudy::with('author:id,name')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('case-studies.show', array_merge(compact('cs'), ['showWipe' => true]));
    }
}
