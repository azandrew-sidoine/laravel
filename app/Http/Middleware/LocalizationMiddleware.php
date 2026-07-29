<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class LocalizationMiddleware
{
    /** @var string[] */
    const SUPPORTED_LOCALES = ['en', 'fr'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->input('locale') ?? $request->header('X-App-Locale') ?? 'en';
        if ($locale && in_array($locale, self::SUPPORTED_LOCALES)) {
            App::setLocale($locale);
            App::get('events')?->dispatch(new LocaleUpdated($locale));
        }

        return $next($request);
    }
}
