<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @method static create($data)
 * @method static select(array $array)
 * @method static count()
 * @method static find($member_id)
 */
class Member extends Model
{
    protected $guarded = [];
    protected $casts = [
        'birth_date' => DateCast::class,
        'convert_date' => DateCast::class,
        'baptize_date' => DateCast::class
    ];
    use HasFactory;

    public function tides(){
        return $this->hasMany(Tide::class,'id','member_id');
    }

    public function fullName(){
        return $this['first_name'] . ' ' . $this['last_name'];
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
}
