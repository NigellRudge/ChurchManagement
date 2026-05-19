<?php

namespace App\Models;

use App\Casts\GenderCast;
use App\Casts\ImageCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RollCall extends Model
{
    protected $table = 'attendance_sheet_rollcall';
    protected $casts = [
      'gender_id' => GenderCast::class,
      'image' => ImageCast::class
    ];
    use HasFactory;
}
