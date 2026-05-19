<?php

namespace App\Models;

use App\Casts\NullValueCheckCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovidRegistrationSheetItemInfo extends Model
{
    protected $table = 'covid_registration_sheet_item_info';
    protected $casts = [
        'id_number' => NullValueCheckCast::class
    ];
    use HasFactory;
}
