<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($group_id)
 */
class EagleGroupInfo extends Model
{
    protected $table = 'eagle_group_info';
    use HasFactory;
}
