<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    protected $guarded = [];
    protected $casts = [
        'amount_in_base_currency' => MoneyCast::class,
        'amount' => MoneyCast::class
    ];
    use HasFactory;
}
