<?php

use Illuminate\Database\Eloquent\Relations\Relation;

function getClassByType($type)
{
    return Relation::getMorphedModel($type);
}
function getModelByType($id, $type)
{
    $c = Relation::getMorphedModel($type);
    return $c::find($id);
}


if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}



// if (!function_exists('cdn')) {

//     function cdn($path = null) // old implimentation

//     {
//         //return url('/storage'.($path ? '/'.$path : ''));
//         return env('CDN_DOMAIN') . '/' . ($path ? $path : '');
//     }

//     function cdn_url($path = '')
//     {
//         return env('CDN_DOMAIN') . ($path ? '/' . $path : $path);
//     }

//     function cdn_path($path = '')
//     {
//         // return storage_path($path ? '/'.$path : $path);
//         return app()->basePath() . '/../cdn' . ($path ? '/' . $path : $path);
//     }

//     function cdnCategoryImage($id = '0')
//     {
//         return sprintf(cdn('categories/%s.png'), $id);
//     }
// }



function relativeToFullUrl(?string $path, ?string $disk = null) : ?string {
    if(!$path){
      	// return 'https://albarqiq.com/storage/images/859BB438-AE92-46DF-BA41-48230E349138.png';
        return null;
    }
    $value = $path;
    if (url()->isValidUrl($value)) {
        $src = $value;
    } else {
        $src = \Storage::url($value); // disk($this->disk)->
    }
    return $src;
  }