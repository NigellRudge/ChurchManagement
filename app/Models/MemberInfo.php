<?php

namespace App\Models;

use App\Casts\GenderCast;
use App\Casts\ImageCast;
use App\Casts\NullValueCheckCast;
use App\Casts\YesNoCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @method static select(array $array)
 * @method static find($member_id)
 * @method static whereNull(string $string)
 * @property mixed|string image
 */
class MemberInfo extends Model
{
    protected $table = 'member_info';
    protected $casts = [
      'id_number' => NullValueCheckCast::class,
      'baptized_date' => NullValueCheckCast::class,
        'baptized' => YesNoCast::class,
      'email' => NullValueCheckCast::class,
      'gender' => GenderCast::class,
      'image' => ImageCast::class
    ];
    use HasFactory;

    public function memberImage(){
        if(isset($this->image)){
            return asset(Storage::url('uploads/images/users/'. $this->image));
        }
         if($this['gender_id'] == 1) {
             return asset('storage/placeholder-male.jpg') ;
         }
         return asset('storage/placeholder-female.png');
    }

}
