<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @method static select(string[] $array)
 * @method static find($id)
 */
class InfantDedicationInfo extends Model
{
    protected $table = 'infant_dedication_info';
    use HasFactory;
    protected $casts = [
        'dedication_date' => DateCast::class,
    ];

    public function motherImage(){
        if(isset($this->mother_image)){
            return asset(Storage::url('uploads/images/users/'. $this->mother_image));
        }
        return asset('storage/placeholder-female.png');
    }

    public function image(){
        if(isset($this->image)){
            return asset(Storage::url('uploads/images/users/'. $this->image));
        }
        if($this['gender_id'] == 1) {
            return asset('storage/placeholder-male.jpg') ;
        }
        return asset('storage/placeholder-female.png');
    }
    public function fatherImage(){
        if(isset($this->father_image)){
            return asset(Storage::url('uploads/images/users/'. $this->father_image));
        }
        return asset('storage/placeholder-male.jpg') ;

    }
}
