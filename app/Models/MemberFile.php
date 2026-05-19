<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($id)
 */
class MemberFile extends Model
{
    protected $table = 'member_files';
    protected $guarded = [];
    use HasFactory;
}
