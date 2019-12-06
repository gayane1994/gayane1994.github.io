<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Intervention\Image\ImageManagerStatic as Image;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function fileUpload($imageFile){
        if ($imageFile) {
            $uploadedImage = $this->imageCheck($imageFile);

            if (empty($uploadedImage)) {
                $uploadedImage = 'invalid';
            }
        }

        return $uploadedImage;
    }
    public function imageCheck($image){
        $imageName        = '';
        $extension        = $image->getClientOriginalExtension();
        $imageFormatArray = ["jpeg", "jpg", "png"];

        if (in_array($extension, $imageFormatArray)) {
            $imageResize = Image::make($image->getRealPath());
            $imageName   = uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;

            $imageResize->save(public_path('images/uploads/' . $imageName));

            return $imageName;
        } else {
            return $imageName;
        }
    }

}
