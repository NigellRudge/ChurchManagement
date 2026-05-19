<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 * @method static create(array $data)
 * @method static select(string[] $array)
 */
class WorkGroup extends Model
{
    protected $guarded = [];
    use HasFactory;
}
