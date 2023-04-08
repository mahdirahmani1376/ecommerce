<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($locale = session()->has('locale')) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
