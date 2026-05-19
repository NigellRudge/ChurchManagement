<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovidRegistrationSheetInfo extends Model
{
    protected $table = 'covid_registration_sheet_info';
    protected $casts = [
        'date' => DateCast::class
    ];
    use HasFactory;
}
