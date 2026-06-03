<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class ThemeSelector
{
    public function handle($request, Closure $next)
    {
        if (session()->get('admin_theme') === 'v2') {
            // Prepend the admin_v2 path so Laravel searches it first for views
            $path = resource_path('views/admin_v2');
            if (is_dir($path)) {
                View::getFinder()->prependLocation($path);
            }
        }
        return $next($request);
    }
}
