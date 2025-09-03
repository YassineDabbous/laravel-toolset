<?php

namespace Yaseen\Toolset\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LangMiddleware
{
    public function handle(Request $request, Closure $next){

        if ($request->hasHeader('Accept-Language') && ($locale = $request->header('Accept-Language')) && in_array($locale, config('settings.available_locales', []))) {
            app()->setLocale($locale);
        }
        // if (Session::has('locale')) {
        //     App::setLocale(Session::get('locale'));
        // }
        return $next($request);
    }
}
