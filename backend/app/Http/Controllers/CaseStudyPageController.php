<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;

class CaseStudyPageController extends Controller
{
    public function index()
    {
        $caseStudies = CaseStudy::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('case-studies.index', ['caseStudies' => $caseStudies, 'showWipe' => true]);
    }

    public function show(string $slug)
    {
        $cs = CaseStudy::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('case-studies.show', ['cs' => $cs, 'showWipe' => true]);
    }
}
