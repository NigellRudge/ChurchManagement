<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $data)
 * @method static find($budget_id)
 */
class Budget extends Model
{
    protected $guarded = [];
    protected $casts = [
        'date' => DateCast::class
    ];
    use HasFactory;
}
