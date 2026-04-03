<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
// use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait ImageProcessing
{
    public function path($id, $folder, $folder2 = null)
    {
        $path = public_path() . '/files' . '/' . $folder . '/' . $id . '/';
        if ($folder2 != null) {

            $path =  $path . '/' .  $folder2 . '/';
        }
        if (!File::exists($path)) {
            mkdir($path, 0777, true);
        }
        return  $path;
    }
    public function deletefile($filename,$id, $folder, $folder2 = null)
    {
        $path = public_path() . '/files' . '/' . $folder . '/' . $id . '/';
        if ($folder2 != null) {
            $path =  $path . '/' .  $folder2 . '/';
        }

        if (File::exists($path.$filename)) {
            File::delete($path.$filename);
        }
    }
   
    public function saveImage($image, $id, $folder, $folder2 = null)
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->read($image);
        $str_random = Str::random(4);
        $imgpath = $str_random . '-' . time() . '.' . $image->getClientOriginalExtension();

        $img->toJpeg(80)->save($this->path($id, $folder, $folder2) .  $imgpath);

        return $imgpath;
    }
    public function aspect4resize($image, $width, $height, $id, $folder, $folder2 = null)
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->read($image);

        $str_random = Str::random(4);

        $img->resize($width, $height);


        $imgpath = $str_random . time() . '.' . $image->getClientOriginalExtension();

        $img->toJpeg(80)->save($this->path($id, $folder, $folder2)  .  $imgpath);
        // $img->save(storage_path('app/imagesfp') . '/' . $imgpath);

        return $imgpath;
    }
    public function aspect4height($image, $width, $height)
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->read($image);

        $str_random = Str::random(4);


        $img->resize(null, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        if ($img->width() < $width) {
            $img->resize($width, null);
        } else if ($img->width() > $width) {
            $img->crop($width, $height, 0, 0);
        }

        $imgpath = $str_random . time() . '.' . $image->getClientOriginalExtension();


        $img->save(storage_path('app/imagesfp') . '/' . $imgpath);
        return $imgpath;
    }
    public function saveImageAndThumbnail($Thefile, $thumb = false, $id = '23123', $folder = 'course', $folder2 = null, $height = null, $width = null)
    {
        $dataX = array();
        if ($height != null && $width != null) {

            $dataX['image'] = $this->aspect4resize($Thefile,  $width, $height, $id, $folder, $folder2);
        } else {
            $dataX['image'] = $this->saveImage($Thefile, $id, $folder, $folder2);
        }

        if ($thumb) {
            $dataX['thumbnailsm'] = $this->aspect4resize($Thefile, 256, 144, $id, $folder, $folder2);
            $dataX['thumbnailmd'] = $this->aspect4resize($Thefile, 426, 240, $id, $folder, $folder2);
            $dataX['thumbnailxl'] = $this->aspect4resize($Thefile, 640, 360, $id, $folder, $folder2);
        }

        return $dataX;
    }
    public function deleteImage($filePath)
    {
        if ($filePath) {
            if (is_file(public_path()::disk('imagesfp')->path($filePath))) {
                if (file_exists(public_path()::disk('imagesfp')->path($filePath))) {
                    unlink(public_path()::disk('imagesfp')->path($filePath));
                }
            }
        }
    }


    public function uploadfile($file, $id = null, $folder = null, $folder2 = null)
    {
    }
    public function applyWatermark($imgewatermark, $imageorginal)
    {
        // $p1 = public_path('\files\1.jpg');
        // $p2 = public_path('\files\watermark.png');

        $watermark = Image::make($imgewatermark);
        $watermark->rotate(45);

        $image = Image::make($imageorginal);
        $image->greyscale();
        // $image->blur(18);

        // $imageWidth = $image->width();
        // $imageHeight = $image->height();
        // $positionX = ($imageWidth - $watermark->width()) / 2;
        // $positionY = ($imageHeight - $watermark->height()) / 2;
        // // $image->insert($imgewatermark, 'center',  number_format($positionX),  number_format($positionY));
        $image->insert($watermark, 'center');
        return $image->response('jpg');
    }
}
