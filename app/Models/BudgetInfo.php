<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static select(string $string)
 * @method static find($id)
 */
class BudgetInfo extends Model
{
    protected $table = 'budget_info';
    protected $casts = [
        'total_amount' => MoneyCast::class,
        'date' => DateCast::class
    ];
    use HasFactory;
}
