<?php

namespace App\Models;

use App\Casts\ImageCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 */
class MemberRelationInfo extends Model
{
    protected $table = 'member_relation_overview';
    protected $casts =[
        'relative_image' => ImageCast::class
    ];
    use HasFactory;
}
