<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, $seedId)
 * @method static insert($data)
 * @method static find($seedId)
 * @method static create($data)
 */
class Seed extends Model
{
    protected $table = 'seeds';
    protected $guarded = [];
    protected $casts = [
        'date' => DateCast::class,
        'amount' => MoneyCast::class
    ];
    use HasFactory;
}
