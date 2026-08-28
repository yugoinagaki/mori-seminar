<?php

namespace App\Http\Controllers;

use App\Models\AnnualTheme;
use App\Models\ThemeYear;

class ThemeController extends Controller
{
    public function index()
    {
        $latestYear = AnnualTheme::max('year');

        $currentThemes = $latestYear
            ? AnnualTheme::where('year', $latestYear)->orderedByRecency()->get()
            : collect();

        $archive = AnnualTheme::when($latestYear, fn ($q) => $q->where('year', '<', $latestYear))
            ->orderBy('year', 'desc')
            ->orderedByRecency()
            ->get()
            ->groupBy('year');

        // Photos keyed by year
        $years = $currentThemes->pluck('year')->concat($archive->keys())->unique();
        $themeYears = ThemeYear::whereIn('year', $years)->get()->keyBy('year');

        return view('theme.index', [
            'latestYear'    => $latestYear,
            'currentThemes' => $currentThemes,
            'archive'       => $archive,
            'themeYears'    => $themeYears,
            'showWipe'      => true,
        ]);
    }
}
