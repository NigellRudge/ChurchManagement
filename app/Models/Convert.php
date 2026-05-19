<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($convertId)
 */
class Convert extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'convert_date' => DateCast::class
    ];

    public function gender(){
        return $this->hasOne(Gender::class,'gender_id','id');
    }
}
