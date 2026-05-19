<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $sheetId)
 */
class VisitorSheetInfo extends Model
{
    protected $table = 'visitors_sheet_info';
    use HasFactory;
}
