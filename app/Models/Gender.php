<?php

namespace App\Models;

use App\Casts\GenderCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    use HasFactory;
    protected $casts = [
        'name' => GenderCast::class
    ];
    protected $table = 'genders';
}
