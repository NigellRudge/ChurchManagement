<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $data)
 */
class ChurchEvent extends Model
{
    protected $guarded = [];
    protected $casts = [
        'date' => DateCast::class,
        'last_payment_date' => DateCast::class,
        'last_registration_date' => DateCast::class
    ];
    use HasFactory;
}
