<?php

namespace App\Models;

use App\Casts\BooleanCast;
use App\Casts\DateCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @method static select(string $string)
 * @method static find($id)
 */
class TransactionInfo extends Model
{
    protected $table = 'transaction_info';
    protected $casts = [
        'transaction_date' => DateCast::class,
        'is_debit' => BooleanCast::class,
//        'debit' => MoneyCast::class,
//        'credit' => MoneyCast::class,
        'amount' => MoneyCast::class,
        'created_at' =>DateCast::class
    ];
    use HasFactory;
}
