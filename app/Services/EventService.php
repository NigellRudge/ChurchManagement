<?php


namespace App\Services;


use App\Models\ChurchEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventService
{

    public function getEvents(array $filterOptions){
        $events = DB::table('church_event_info');
        if(isset($filterOptions['start_date'])){
            $events->whereDate('date','>=',Carbon::parse($filterOptions['start_date']));
        }
        if(isset($filterOptions['end_date'])){
            $events->whereDate('date','<=',Carbon::parse($filterOptions['end_date']));
        }
        if(isset($filterOptions['calendar'])){
            return $events->select('id','title','date as start','date as end','time');
        }
        return $events->select('*');
    }

    public function storeEvent(Request $request,array $data){
        $data['price'] = null;
        $data['registration_price'] = null;
        $data['last_registration_date'] = null;
        $data['last_payment_date'] = null;
        $data['description'] = isset($request['description']) ? $request['description'] : null;
        $data['time'] = Carbon::parse($data['date'])->toTimeString();
        $data['is_paid_event'] = isset($request['is_paid_event']) && $request['is_paid_event'] == 'on';
        $data['should_register'] = isset($request['should_register']) && $request['should_register'] == 'on';
        if($data['should_register']){
            $data['registration_price'] = isset($request['registration_fee']) ? $request['registration_fee'] : null;
            $data['last_registration_date'] = isset($request['last_registration_date']) ? $request['last_registration_date'] : $data['date'];
            //TODO: create registration Sheet
        }
        if($data['is_paid_event']){
            $data['price'] = isset($request['ticket_price']) ? $request['ticket_price'] : null;
            $data['currency_id'] = isset($request['currency_id']) ? $request['currency_id'] : null;
            $data['last_payment_date'] = isset($request['last_payment_date']) ? $request['last_payment_date'] : $data['date'];
        }
        //dd($data);
        return ChurchEvent::create($data);
    }

    public function deleteEvent($eventId){
        return DB::table('church_events')->where('id',$eventId)->delete();
    }
}
