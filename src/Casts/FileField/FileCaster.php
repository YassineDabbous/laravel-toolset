<?php

namespace Ysn\SuperCore\Casts\FileField;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

// https://github.com/sabbir268/laravel-filecaster/blob/main/src/FileCaster.php
class FileCaster implements CastsAttributes
{
    protected $disk;
    public function __construct()
    {
        $this->disk = 'public';
    }
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }
    /**
     * @param  \Illuminate\Http\UploadedFile|null  $value
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_file($value)) {
            // delete old file if exists
            if (isset($attributes[$key])) {
                if (Storage::disk($this->disk)->exists($attributes[$key])) {
                    Storage::disk($this->disk)->delete($attributes[$key]);
                }
            }
            $value = $value->store($model?->getTable() ?? 'file_caster_default_path', ['disk' => $this->disk]);
            return $value;
        } else {
            return $value;
        }
    }
    





    // public function get($model, string $key, $value, array $attributes)
    // {
    //     // $class = $this->getClassName($model);
    //     // return new FileWrapper($value, $model, $key);
    //     return $value;
    // }

    



    // /**
    //  * @param  \Illuminate\Http\UploadedFile|null  $value
    //  */
    // public function set($model, string $key, $value, array $attributes)
    // {
    //     if (is_file($value)) {
    //         // delete old file if exists
    //         if (isset($attributes[$key])) {
    //             if (Storage::disk($this->disk)->exists($attributes[$key])) {
    //                 Storage::disk($this->disk)->delete($attributes[$key]);
    //             }
    //         }
    //         // $file = $value;
    //         // $class = $this->getClassName($model);
    //         // $id = $this->getId($attributes, $model);
    //         // $filenameWithExt = $this->getFileName($value);
    //         // $path = $this->filePath($model, $attributes);
    //         // $value = $value->storeAs(
    //         //     $this->filePath($model, $attributes),
    //         //     $this->getFileName($value),
    //         //     $this->disk
    //         // );
    //         $value = $value->store($model?->getTable() ?? 'avatars', ['disk' => $this->disk]);
    //         return $value;
    //     } else {
    //         return $value;
    //     }
    // }





    // protected function getId($attributes = null, $modelName = null)
    // {
    //     if (isset($attributes['id'])) {
    //         return $attributes['id'];
    //     } else {
    //         $model = $modelName::orderBy('id', 'desc')->first();
    //         if ($model) {
    //             return $model->id + 1;
    //         } else {
    //             return 1;
    //         }
    //     }
    // }

    
    //  protected function getClassName(Model $model): string
    // {
    //     return strtolower(substr(get_class($model), strrpos(get_class($model), '\\') + 1));
    // }

    // protected function filePath(Model $model, $attributes)
    // {
    //     $definedPath = config('filecaster.path');
    //     if ($definedPath == 'by_model_name_and_id') {
    //         return $this->pathByModelNameAndId($model, $attributes);
    //     } elseif ($definedPath == 'defined_path_in_model') {
    //         return $this->pathByDefinedPathInModel($model);
    //     } else {
    //         throw new \Exception("Invalid path defined in config");
    //     }
    // }

    // protected function pathByModelNameAndId(Model $model, $attributes)
    // {
    //     $class = $this->getClassName($model);
    //     $id = $this->getId($attributes, $model);
    //     $path = $class . '/' . $id;
    //     return $path;
    // }

    // protected function pathByDefinedPathInModel(Model $model)
    // {
    //     if (!isset($model->fileUploadPath)) {
    //         throw new \Exception("Model does not have a variable named fileUploadPath");
    //     }
    //     return $model->fileUploadPath;
    // }

    
    // protected function getFileName($file)
    // {
    //     $fileName = config('filecaster.file_name');
    //     $namePrefix = $this->namePrefix;
    //     $name = '';
    //     if ($fileName == 'original_file_name') {
    //         $name = $file->getClientOriginalName();
    //     } elseif ($fileName == 'hash_name') {
    //         $name = $file->hashName();
    //     } else {
    //         throw new \Exception("Invalid file name defined in config");
    //     }
    //     if ($namePrefix && $namePrefix != '') {
    //         $name = $namePrefix . '-' . $name;
    //     }
    //     return $name;
    // }
}