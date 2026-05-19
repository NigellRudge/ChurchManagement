<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, $memberId)
 */
class MemberFileInfo extends Model
{
    protected $table = 'member_file_info';
    use HasFactory;
}
