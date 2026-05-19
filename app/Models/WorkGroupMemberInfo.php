<?php

namespace App\Models;

use App\Casts\ImageCast;
use App\Casts\NullValueCheckCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkGroupMemberInfo extends Model
{
    protected $table = 'work_group_member_info';
    protected $casts = [
      'image' => ImageCast::class,
      'id_number' => NullValueCheckCast::class
    ];
    use HasFactory;
}
