<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 * @method static select(string $string, string $string1, string $string2, string $string3, string $string4, string $string5, string $string6, string $string7)
 */
class OfferingInfo extends Model
{
    protected $table = 'offering_info';
    protected $casts = [
        'date' => DateCast::class,
        'srd_amount' => MoneyCast::class,
        'usd_amount' => MoneyCast::class,
        'euro_amount' => MoneyCast::class,
        'total_amount' => MoneyCast::class,
    ];
    use HasFactory;
}
