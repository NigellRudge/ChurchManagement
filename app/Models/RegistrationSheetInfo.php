<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationSheetInfo extends Model
{
    protected $table = 'registration_sheet_info';
    protected $casts = [
        'last_registration_date' => DateCast::class
    ];
    use HasFactory;
}
