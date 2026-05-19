<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(array $data)
 * @method static create(array $data)
 */
class Offering extends Model
{
    protected $table = 'offerings';
    protected $guarded = [];
    protected $casts = [
        'date' => DateCast::class,
        'srd_amount' => MoneyCast::class,
        'usd_amount' => MoneyCast::class,
        'euro_amount' => MoneyCast::class,
    ];
    use HasFactory;
}
