<?php

namespace App\Models;

use App\Casts\GenderCast;
use App\Casts\ImageCast;
use App\Casts\NullValueCheckCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static count()
 */
class EagleMemberInfo extends Model
{
    protected $table = 'eagle_member_info';
    protected $casts = [
      'gender' => GenderCast::class,
        'image' => ImageCast::class,
        'email' => NullValueCheckCast::class
    ];
    use HasFactory;
}
