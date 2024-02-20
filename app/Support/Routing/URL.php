<?php

namespace App\Support\Routing;

use Drewlabs\Core\Helpers\Str;
use Drewlabs\Core\Helpers\URI;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\URL as FacadesURL;
use InvalidArgumentException;
use RuntimeException;

class URL
{
    /**
     * Wrap {@see URL} facade replacing url scheme if app url scheme is https and the generated
     * url scheme is http
     *
     * @param string $name
     * @param array $parameters
     * @param bool|null $absolute
     * @return string
     * @throws InvalidArgumentException
     * @throws BindingResolutionException
     */
    public static function route(string $name, $parameters = [], bool $absolute = true)
    {
        $url = FacadesURL::route($name, $parameters, $absolute);
        $scheme = parse_url($url, PHP_URL_SCHEME);
        // If the scheme is not provided, we don't continue any further
        if (false === $scheme) {
            return $url;
        }
        // If the application url scheme is https and the generated url scheme is http
        // We replace the http:// of the generated url with https://
        if ((Str::startsWith($url, 'http://') || $scheme === 'http') && (self::appURLScheme() === 'https')) {
            // We replace the 'http://' with 'https://' scheme
            return null !== ($subpath = Str::after('http://', $url)) ? 'https://' . $subpath : $url;
        }
        return $url;
    }

    /**
     * Creates a signed url from route parameters
     * 
     * @param string $name 
     * @param array $parameters 
     * @param bool $absolute 
     * @param callable|null $resolveKeyFn 
     * @return string|false 
     * @throws InvalidArgumentException 
     * @throws BindingResolutionException 
     * @throws RuntimeException 
     */
    public static function signedRoute(string $name, $parameters = [], bool $absolute = true, callable $resolveKeyFn = null)
    {
        $url = static::route($name, $parameters, $absolute);
        return URI::withSignature($url, null === $resolveKeyFn ? function () {
            return config('app.key');
        } : function () use ($resolveKeyFn) {
            return call_user_func($resolveKeyFn);
        });
    }

    /**
     *
     * @return array|string|int|null|false
     * @throws InvalidArgumentException
     * @throws BindingResolutionException
     */
    private static function appURLScheme()
    {
        $url = config('app.url', env('APP_URL'));
        return parse_url($url, PHP_URL_SCHEME);
    }
}
