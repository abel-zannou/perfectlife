<?php

namespace App\Trait;
// use Image;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait HandleProductImage
{
    public function handleProductImage($imagedata)
    {
        // create image manager with desired driver
        $manage = new ImageManager(new Driver());

        if($imagedata instanceof UploadedFile)
        {
            $imageName = hexdec(uniqid()) . '.' . $imagedata->getClientOriginalExtension();
            $path = 'upload/products/';
            $fullpath = storage_path('app/public/' . $path) ;
            if(!file_exists($fullpath))
            {
                mkdir($fullpath, 0755, true);
            }

            $manage->read($imagedata)
                ->resize(960, 711)
                ->save($fullpath.$imageName);

            return asset('storage/' .$path.$imageName);
        }

        // Si une URL est directement fournie
        if(is_string($imagedata))
        {
            return $imagedata;
        }

        return null;
    }
}
