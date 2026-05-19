<?php

namespace App\Models;

use App\Casts\GenderCast;
use App\Casts\ImageCast;
use App\Casts\NullValueCheckCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @method static select(string $string)
 */
class ServiceClubMemberInfo extends Model
{
    protected $table = 'service_club_member_info';
    use HasFactory;
    protected $casts = [
        'image' => ImageCast::class,
        'gender' => GenderCast::class,
        'id_number' => NullValueCheckCast::class
    ];

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
