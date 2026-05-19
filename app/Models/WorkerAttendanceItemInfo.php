<?php

namespace App\Models;

use App\Casts\ImageCast;
use App\Casts\NullValueCheckCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @method static where(string $string, string $string1, $sheetId)
 */
class WorkerAttendanceItemInfo extends Model
{
    protected $table = 'worker_attendance_item_info';
    protected $casts = [
      'member_image' => ImageCast::class,
      'id_number' => NullValueCheckCast::class
    ];
    use HasFactory;

    public function memberImage(){
        if(isset($this->member_image)){
            return asset(Storage::url('uploads/images/users/'. $this->member_image));
        }
        if($this['gender_id'] == 1) {
            return asset('storage/placeholder-male.jpg') ;
        }
        return asset('storage/placeholder-female.png');
    }
}
