<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static select(string[] $array)
 */
class WorkerAttendanceSheet extends Model
{
    protected $table = 'worker_attendance_sheets';
    protected $guarded = [

    ];
    protected $casts = [
      'date' => DateCast::class
    ];
    use HasFactory;
}
