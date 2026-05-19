<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Casts\ImageCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static select(string $string, string $string1, string $string2, string $string3, string $string4, string $string5, string $string6, string $string7, string $string8)
 */
class SeedInfo extends Model
{
    protected $table = 'seeds_info';
    protected $casts = [
        'date' => DateCast::class,
        'amount' => MoneyCast::class,
        'image' => ImageCast::class,
        'amount_in_base_currency' => MoneyCast::class,
    ];
    use HasFactory;
}
