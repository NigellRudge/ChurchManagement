<?php

namespace App\Models;

use App\Casts\BooleanCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @method static find($id)
 * @method static select(string $string, \Illuminate\Database\Query\Expression $raw)
 */
class SubAccountInfo extends Model
{
    protected $table = 'sub_accounts_info';
    protected $casts = [
        'balance' => MoneyCast::class,
        'can_delete' => BooleanCast::class
    ];
    use HasFactory;
}
