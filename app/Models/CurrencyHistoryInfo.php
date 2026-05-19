<?php

namespace App\Models;

use App\Casts\BooleanCast;
use App\Casts\StatusCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, $currencyId)
 */
class CurrencyHistoryInfo extends Model
{
    protected $table = 'currency_history_info';
    protected $casts = [
        'active' => StatusCast::class
    ];
    use HasFactory;
}
