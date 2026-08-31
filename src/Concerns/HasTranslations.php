<?php

namespace Yaseen\Toolset\Concerns;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait HasTranslations
{
    protected bool $ignoreTranslation = false;

    public function withoutTranslation(){
        $this->ignoreTranslation = true;
        return $this;
    }
    /**
     * Get a translated attribute.
     *
     * This is a helper method to safely access a translated value.
     *
     * @param string $key The attribute to translate (e.g., 'name', 'description').
     * @param string|null $locale The locale to use (e.g., 'ar', 'en'). Defaults to the current app locale.
     * @return mixed|null
     */
    public function getTranslated(string $key, ?string $locale = null)
    {
        if($this->ignoreTranslation){
            return null;
        }

        $locale = $locale ?? App::getLocale();
        // return $locale;
        
        // The translations column should be cast to 'array' in the model.
        return $this->translations[$key][$locale] ?? null;
    }

    /**
     * Override the default Eloquent attribute getter.
     *
     * This magic method intercepts requests for attributes. If a translation
     * exists for the requested attribute, it returns the translated value.
     * Otherwise, it falls back to the default parent behavior.
     *
     * @param string $key
     * @return mixed
     */
    // public function getAttribute($key)
    // {
    //     // First, check if a translation exists for this requested attribute.
    //     $translation = $this->getTranslated($key);
    //     
    //     if ($translation !== null) {
    //         return $translation;
    //     }

    //     // If no translation is found, call the original Eloquent getAttribute method.
    //     // This will return the default column value (e.g., $this->name) or an accessor.
    //     // return parent::getAttribute($key);
    // }

    /**
     * Resolve a locale-correct value for a translatable key.
     *
     * This is the single owner of translation resolution and implements the full
     * fallback chain used by summary/detail cards:
     *
     * 1. the explicitly requested locale (or the resolved request locale),
     * 2. the configured application fallback locale,
     * 3. the first non-empty value in the translations map for the key,
     * 4. the model's top-level column for the key (if present),
     * 5. a guaranteed non-null placeholder so a card never renders empty.
     *
     * @param  string  $key  The translatable attribute (e.g. 'name', 'title').
     * @param  string|null  $locale  Explicit locale; defaults to the resolved request locale.
     */
    public function localized(string $key, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        $direct = $this->getTranslated($key, $locale);
        if ($direct !== null && $direct !== '') {
            return $direct;
        }

        $fallback = config('app.fallback_locale', 'en');
        if ($fallback !== $locale) {
            $viaFallback = $this->getTranslated($key, $fallback);
            if ($viaFallback !== null && $viaFallback !== '') {
                return $viaFallback;
            }
        }

        $translations = $this->translations[$key] ?? [];
        foreach ($translations as $value) {
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        if (array_key_exists($key, $this->attributes) && $this->attributes[$key] !== null && $this->attributes[$key] !== '') {
            return (string) $this->attributes[$key];
        }

        return '—';
    }
}