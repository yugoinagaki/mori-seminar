<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnnualTheme;

class AnnualThemeController extends Controller
{
    public function latest()
    {
        $theme = AnnualTheme::orderBy('year', 'desc')->first();

        if (! $theme) {
            return response()->json(null, 204);
        }

        return response()->json($theme);
    }
}
