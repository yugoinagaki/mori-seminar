<?php

namespace App\Http\Controllers;

use App\Models\AnnualTheme;

class ThemeController extends Controller
{
    public function index()
    {
        $theme = AnnualTheme::orderBy('year', 'desc')->first();

        return view('theme.index', ['theme' => $theme, 'showWipe' => true]);
    }
}
