<?php

namespace App\Models;

use App\Casts\BooleanCast;
use App\Casts\DateCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static select(string $string, \Illuminate\Database\Query\Expression $raw)
 */
class MainAccountInfo extends Model
{
    protected $table = 'main_account_info';
    protected $casts = [
        'sum_debit' => MoneyCast::class,
        'sum_credit' => MoneyCast::class,
        'balance' => MoneyCast::class,
    ];
    use HasFactory;
}
