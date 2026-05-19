<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 */
class AttendanceSheetInfo extends Model
{
    protected $table = 'attendance_sheet_info';
    /**
     * @var mixed
     */
    private $id;
    use HasFactory;
}
