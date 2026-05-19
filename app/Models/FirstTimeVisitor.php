<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstTimeVisitor extends Model
{
    protected $table = 'visitors';
    protected $casts = [
        'date' => DateCast::class
    ];
    protected $guarded = [

    ];
    use HasFactory;
}
