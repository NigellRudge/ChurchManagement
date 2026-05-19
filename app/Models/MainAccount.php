<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($accountId)
 * @method static insert(array[] $array)
 */
class MainAccount extends Model
{
    protected $guarded = [];
    use HasFactory;
}
