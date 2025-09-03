<?php

use Illuminate\Contracts\Support\Arrayable;
use League\CommonMark\CommonMarkConverter;


if(!function_exists('___')){
    function ___(string $module, string $key, $replace = [], $locale = null){
        if(App::currentLocale() === 'en'){
            $x = explode('.', $key);
            return end($x);
        }
        //
        return app('translator')->get('super'.$module.'::default.'.$key, $replace, $locale);
    }
}
// if(!function_exists('___')){
//     function ___(string $key, $replace = [], $locale = null){
//         if(App::currentLocale() === 'en'){
//             $x = explode('.', $key);
//             return end($x);
//         }
//         //'superinvoice::default.'.
//         return app('translator')->get($key, $replace, $locale);
//     }
// }

if (! function_exists('var2val')) {
    /**
     * Convert provided string with optional variables to real values.
     *
     * @param  string  $needle  The needle text that may contain variables.
     * @param  ArrayAccess|Arrayable|array  $haystack  The variables that should be checked against.
     * @param  string  $fallback  The default value in case no match was found.
     * @return  string
     */
    function var2val(string $needle = null, ArrayAccess|Arrayable|array $haystack = [], string $fallback = null)
    {
        // check if an empty string was supplied
        if (is_string($needle) && empty(trim($needle))) {
            return null;
        }

        // set needle as output by default
        $output = $needle;
        $default ??= $needle;

        // optionally convert haystack values to array
        $haystack = ! is_array($haystack)
            ? $haystack->toArray()
            : $haystack;

        // check if needle is array
        if (is_array($needle)) {
            // loop trough all needle values
            foreach ($output as $key => $value) {
                // convert all needle values individually and iteratively
                $output[$key] = var2val($value, $haystack, $default);
            }

            return $output;

        // check for textual needle
        } elseif (is_string($output)) {

            // check if the needle is actually a data key
            if (isset($haystack[$needle])) {
                $output = trim($haystack[$needle]);
            }

            // check for a double colon ":", which indicates variable usage
            if (str_contains($output, ':')) {
                // replace the variables using the provided data
                $output = trans($output, $haystack);
            }

            // return the trimmed output or (provided) default value
            return $output !== $needle ? $output : $default;
        }

        return $output;
    }
}

if(!function_exists('calculateReadingTime')){
    function calculateReadingTime($content){
        $count = count(explode(' ', $content));
        $time = $count / 200; // 200 words per minute
        $minutes = intval($time);
        $b = ($time-$minutes);
        $plus = round($b);
        return ($minutes+$plus);
    }
}


if(!function_exists('hashtagsFromText')){
    function hashtagsFromText($str){
        $pattern = '/#(\w*[a-zA-Z-أ-إ-آ-ا-ب-ت-ث-ج-ح-خ-د-ذ-ر-ز-س-ش-ص-ض-ط-ظ-ع-غ-ف-ق-ك-ل-م-ن-ه-و-لا-لا-لآ-لأ-لإ-ى-ي-ئ-ة-ء-ؤ_]+)/';
        //$pattern = "/#+([a-zA-Z0-9_]+)/";
        preg_match_all($pattern, $str, $matches);
        return $matches[1];
    }
}




if(!function_exists('textToHashtagable')){
    function textToHashtagable($str){
        //$pattern = "/#+([a-zA-Z0-9_]+)/";
        $pattern = '/#(\w*[a-zA-Z-أ-إ-آ-ا-ب-ت-ث-ج-ح-خ-د-ذ-ر-ز-س-ش-ص-ض-ط-ظ-ع-غ-ف-ق-ك-ل-م-ن-ه-و-لا-لا-لآ-لأ-لإ-ى-ي-ئ-ة-ء-ؤ_]+)/';
        $str = preg_replace($pattern, '<a href="' . url('/hashtag/$1') . '">$0</a>', $str);
        return $str;
    }
}



if(!function_exists('shortTxt')){
    function shortTxt($txt, $nbr){
        mb_internal_encoding("UTF-8");
        // <!-- added for new one style
        if (strlen($txt) > $nbr) {
            return mb_substr($txt, 0, $nbr - 1) . '...';
        }

        // -->
        $postfix = strlen($txt) > $nbr ? '...' : '';
        //$txt = mb_substr($txt,0,$nbr );
        $tab = explode(' ', $txt);
        $txt = '';
        for ($index = 0; $index < count($tab); $index++) {
            if (strlen($txt) < $nbr) {
                $txt .= ' ' . $tab[$index];
            } else {
                break;
            }

        }
        return $txt . $postfix;
    }
}



if(!function_exists('markdownToText')){
    function markdownToText($markdown) : string {
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $html = $converter->convert($markdown);
        return strip_tags($html);
    }
}
