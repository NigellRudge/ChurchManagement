<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Storage;

class ImageCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        if(isset($value)){
            return asset(Storage::url('uploads/images/users/'. $value));
        }
        if($model['gender_id'] == 1) {
            return asset('storage/placeholder-male.jpg') ;
        }
        return asset('storage/placeholder-female.png');
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
