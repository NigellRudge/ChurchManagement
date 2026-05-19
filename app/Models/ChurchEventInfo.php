<?php

namespace App\Models;

use App\Casts\CalendarCast;
use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($eventId)
 */
class ChurchEventInfo extends Model
{
    protected $table = 'church_event_info';
    protected $casts = [
        'date' => CalendarCast::class
    ];
    use HasFactory;
}
