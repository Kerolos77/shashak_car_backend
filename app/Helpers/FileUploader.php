<?php

namespace App\Helpers;

class FileUploader
{
   public static function upload($model, $file, $collection, $type = 'single_image')  
   {
    // Return early if file is null or empty
    if ($file === null || (is_array($file) && empty($file))) {
        return;
    }
    
    if ($type == 'single_image') {
        $model->addMedia($file)->toMediaCollection($collection);
    } else {
        // Ensure $file is an array before foreach
        if (is_array($file) || is_object($file)) {
            foreach ($file as $image) {
                $model->addMedia($image)->toMediaCollection($collection);
            }
        }
    }
   }
}
