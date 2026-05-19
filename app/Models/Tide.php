<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 * @method static create(array $data)
 */
class Tide extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'date' => DateCast::class
    ];

    public function member(){
        return $this->belongsTo(Member::class,'member_id','id');
    }

    public function currency(){
        return $this->hasOne(Currency::class,'id','currency_id');
    }
}
