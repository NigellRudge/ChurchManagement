<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItemInfo extends Model
{
    protected $table = 'budget_item_info';
    protected $casts = [
        'amount_in_base_currency' => MoneyCast::class,
        'amount' => MoneyCast::class,
        'created_at' => DateCast::class
    ];
    use HasFactory;
}
