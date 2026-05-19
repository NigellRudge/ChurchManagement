<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DateCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {

        if($value == null){
            return null;
        }
        $temp = Carbon::parse($value);
        $m = $temp->month< 10 ? "0$temp->month": $temp->month;
        $d = $temp->day< 10 ? "0$temp->day": $temp->day;
        return "$m/$d/$temp->year";
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {

        return Carbon::parse($value)->toDateString();
    }
}
