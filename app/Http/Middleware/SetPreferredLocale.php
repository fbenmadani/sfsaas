<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SetPreferredLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        /*
        // If user has locale then set it
        if ($userLocale = optional(auth()->user())->locale) {
            app()->setLocale($userLocale);

            return $next($request);
        }

        */

        // Otherwise auto-detect locale between browser and supported languages
        $languages = array_column(config('saas.supported_languages'), 'short_code');
        $locale = $request->getPreferredLanguage($languages);

        app()->setLocale($locale);

        return $next($request);
    }
}
