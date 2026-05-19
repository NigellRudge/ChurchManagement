<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static select(array $array)
 * @property mixed name
 * @property mixed code
 */
class Country extends Model
{
    use HasFactory;
    protected $guarded = [];
}
