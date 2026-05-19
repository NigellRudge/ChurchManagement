<?php


namespace App\Services;


use App\Models\ChurchEvent;
use App\Models\ChurchEventInfo;
use Carbon\Carbon;

class ChurchEventService
{
    public function __construct()
    {

    }

    public function getAllEvents(array  $data){
        $events = ChurchEventInfo::select([
            'id',
            'title',
            'location',
            'date',
            'time'
        ]);
        return $events;
    }

    public function storeEvent(array $data){
        $data['time'] = Carbon::parse($data['start_date'])->toTimeString('minutes');
        //dd($data);
        $event = ChurchEvent::create($data);
        return $event;
    }
}
