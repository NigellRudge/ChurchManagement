<?php

namespace App\Models;

use App\Casts\BooleanCast;
use App\Casts\DateCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static select(string $string)
 * @method static create(array $array)
 * @method static find($id)
 */
class Transaction extends Model
{
    protected $guarded = [];
    protected $casts = [
        'transaction_date' => DateCast::class,
        'is_debit' => BooleanCast::class,
        'amount' => MoneyCast::class,
    ];
    use HasFactory;
}
