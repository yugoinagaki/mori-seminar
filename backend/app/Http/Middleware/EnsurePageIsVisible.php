<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePageIsVisible
{
    public function handle(Request $request, Closure $next, string $key): Response
    {
        if (!SiteSetting::instance()->isPageVisible($key)) {
            abort(404);
        }

        return $next($request);
    }
}
