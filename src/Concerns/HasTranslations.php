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
    //     // First, check if a translation exists for this key in the current locale.
    //     $translation = $this->getTranslated($key);
        
    //     if ($translation !== null) {
    //         return $translation;
    //     }

    //     // If no translation is found, call the original Eloquent getAttribute method.
    //     // This will return the default column value (e.g., $this->name) or an accessor.
    //     return parent::getAttribute($key);
    // }
}