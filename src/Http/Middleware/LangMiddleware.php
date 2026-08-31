<?php

namespace Yaseen\Toolset\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LangMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $available = $this->availableLocales();

        if ($request->filled('__language')) {
            $override = strtolower(trim((string) $request->__language));
            if (in_array($override, $available, true)) {
                app()->setLocale($override);

                return $next($request);
            }
        }

        if ($request->hasHeader('Accept-Language')) {
            $locale = $this->resolveFromHeader($request->header('Accept-Language'), $available);
            if ($locale !== null) {
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }

    /**
     * @param  array<int,string>  $available
     */
    protected function resolveFromHeader(?string $header, array $available): ?string
    {
        if ($header === null || $header === '') {
            return null;
        }

        $parts = explode(',', $header);
        $candidates = [];

        foreach ($parts as $part) {
            $segment = trim($part);
            if ($segment === '') {
                continue;
            }

            $quality = 1.0;
            if (str_contains($segment, ';')) {
                [$tag, $params] = explode(';', $segment, 2);
                $tag = trim($tag);
                foreach (explode(';', $params) as $param) {
                    if (str_starts_with($param, 'q=')) {
                        $quality = (float) substr($param, 2);
                        break;
                    }
                }
            } else {
                $tag = $segment;
            }

            $base = strtolower(explode('-', $tag)[0]);
            $candidates[] = ['locale' => $base, 'quality' => $quality];
        }

        usort($candidates, static fn ($a, $b) => $b['quality'] <=> $a['quality']);

        foreach ($candidates as $candidate) {
            if (in_array($candidate['locale'], $available, true)) {
                return $candidate['locale'];
            }
        }

        return null;
    }

    /**
     * @return array<int,string>
     */
    protected function availableLocales(): array
    {
        $configured = config('settings.available_locales');

        if (is_array($configured) && $configured !== []) {
            return array_values(array_map('strtolower', $configured));
        }

        return ['en', 'ar'];
    }
}
