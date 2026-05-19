<?php

namespace App\Models;

use App\Casts\ModuleNameCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Module extends Model
{
    protected $table = 'modules';
    protected $guarded = [];
    protected $casts = [];
    use HasFactory;
}
