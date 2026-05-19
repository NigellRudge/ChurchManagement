<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModuleAccess extends Model
{
    protected $table = 'user_module_access';
    protected $guarded = [];
    use HasFactory;
}
