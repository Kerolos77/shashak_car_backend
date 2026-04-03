<?php

namespace App\Http\Middleware;

use Closure;

class SetPreferredLocale
{
    public function handle($request, Closure $next)
    {
        if ($request->query('change_language') != null) {
            session()->put('locale', $request->query('change_language'));
        }
        if(session()->get('locale') != null) {
            $locale = session()->get('locale');
        } else {
            $locale = 'ar';
        }
        app()->setLocale($locale);
        return $next($request);
    }
}
