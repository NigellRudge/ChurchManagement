<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @method static select(string $string)
 * @method static create(array $array)
 * @method static find($accountId)
 * @method static insert(array $array)
 */
class SubAccount extends Model
{
    protected $table = 'sub_accounts';
    protected $guarded = [

    ];
    use HasFactory;
}
