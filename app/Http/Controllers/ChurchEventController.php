<?php

namespace App\Http\Controllers;

use App\Exports\RegistrationSheetExport;
use App\Exports\VisitorsSheetExport;
use App\Models\ChurchEvent;
use App\Models\ChurchEventInfo;
use App\Models\Currency;
use App\Models\EventType;
use App\Models\EventTypeInfo;
use App\Models\RegistrationSheetInfo;
use App\Services\EventService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ChurchEventController extends CommonController
{
    private $eventService;
    public function __construct(EventService $service)
    {
        parent::__construct();
        $this->eventService = $service;
        $this->data['controller_name'] = "Events";
        $this->data['category_name'] = "Planning";
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if($request->ajax()){
//            $events = ChurchEventInfo::select([
//                    'id',
//                    'title',
//                    'location',
//                    'date',
//                    'time'
//            ]);
//            //dd($events);
            return DataTables::of($this->eventService->getEvents($request->all()))
                ->addColumn('actions', function ($row){
                    $editUrl = route('events.edit',['event' => $row->id]);
                    $deleteUrl = route('events.delete',['event' => $row->id]);
                    $showUrl = route('events.show',['event' => $row->id]);;
                    return
                        "<a class='btn btn-primary rounded btn-sm text-white font-weight-bold mr-1' href='$showUrl'>
                            <i class='fa fa-eye'></i>
                          </a>"
                        ."<a class='btn-teal btn btn-sm rounded text-white  font-weight-bold mr-1 ' href='#'>
                          <i class='fa fa-edit'></i>
                          </a>"
                        ."<a class='btn btn-danger btn-sm rounded text-white font-weight-bold'" .
                        " data-id='$row->id' data-title='$row->title' data-date='$row->date' href='#' onclick='openRemoveModal(event)'>
                          <i class='fa fa-trash'></i>
                          </a>";

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        $this->data['action_name'] = 'Index';
        $this->data['currencies'] = Currency::all();
        return view('events.index')->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->data['action_name'] = "Create";
        return view('events.create')->with('data',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'location' => 'required',
            'start_date' => 'required'
        ]);
        $data['time'] = Carbon::parse($data['start_date'])->toTimeString('minutes');
        //dd($data);
        $event = ChurchEvent::create($data);
        return redirect(route('events.index'))->with('success','record added');

    }

    public function StoreAjax(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'location' => 'required',
            'date' => 'required',
        ]);
        $result = $this->eventService->storeEvent($request,$data);
        if($result != null){
            return response(['message'=>'Event Saved'],201);
        }
        return response(['message'=>'Something went wrong'],401);

    }

    public  function destroyAjax(Request $request){
        $data = $request->validate([
           'remove_event_id' => 'required'
        ]);
        $result = $this->eventService->deleteEvent($data['remove_event_id']);
        if($result != null){
            return response(['message'=>'Event Removed'],201);
        }
        return response(['message'=>'Something went wrong'],401);
    }


    /**
     * Display the specified resource.
     *
     * @param $eventId
     * @return Application|Factory|View
     */
    public function show($eventId)
    {
        $this->data['action_name'] = 'Show';
        $this->data['event'] = ChurchEventInfo::findOrFail($eventId);
        //dd($this->data);
        return view('events.show')->with('data',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ChurchEvent $churchEvent
     * @return Response
     */
    public function edit(ChurchEvent $churchEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ChurchEvent $churchEvent
     * @return Response
     */
    public function update(Request $request, ChurchEvent $churchEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ChurchEvent $churchEvent
     * @return Response
     */
    public function destroy(ChurchEvent $churchEvent)
    {
        //
    }

    public function delete(ChurchEvent $event){
        return view('events.delete')->with('event',$event);
    }

    public function calendar(Request $request){
        if($request->ajax()){
            return response()->json(['events' =>$this->eventService->getEvents($request->all())->get()],201);
        }
        $this->data['action_name'] = "Calendar";
        $this->data['currencies'] = Currency::all();
        return view('events.calendar')->with('data',$this->data);
    }

    public function eventTypesIndex(Request $request){
        if($request->ajax()){
            $types = DB::table('event_type_info')
                ->select([
                    'id',
                    'name',
                    'code',
                    'description',
                    'repeated',
                    'interval',
                    'status'
                ])->get();
            //dd($types);
            return DataTables::of($types)
                ->addColumn('actions', function ($row){
                    $editUrl = route('event-type.edit',['eventType' => $row->id]);
                    $deleteUrl = route('event-type.delete',['eventType' => $row->id]);
                    $showUrl = '';
                    return "<button class='btn btn-danger btn-sm text-white rounded font-weight-bold mr-1 text-xs' onclick='openRemoveModal(event)'  data-id='$row->id' data-name='$row->name'>remove</button>"
                        ."<button class='btn btn-teal btn-sm text-white rounded font-weight-bold mr-1 text-xs' onclick='openEditModal(event)'  data-id='$row->id' data-name='$row->name'>edit</button>";

                })
                ->rawColumns(['actions'])
                ->make(true);
       }
        $this->data['controller_name'] = 'Event Types';
        $this->data['category_name'] = 'Config';
        $this->data['action_name'] = 'Index';
        return view('config.eventtypes.index')->with('data',$this->data);
    }

    public  function eventTypesCreate(){
        $this->setTypeInfo('Create');
        return view('config.eventtypes.create')->with('data',$this->data);
    }

    public function eventTypesStore(Request $request){
        $request->validate([
           'name' => 'required|min:5|max:50',
           'code' => 'required|max:8'
        ]);
        $eventType = new EventType();
        $eventType['name'] = $request['name'];
        $eventType['code'] = $request['code'];
        if(isset($request['repeated'])){
            $eventType['repeated'] = 1;
            if($request['repeated'] == true){
                $eventType['period'] = $request['interval'];
            }
        }
        if(isset($request['description']) && strlen($request['description']) > 0){
            $eventType['description'] = $request['description'];
        }
        $eventType->save();
        return redirect(route('event-type.index'))->with('success', 'record added');

    }

    public function eventTypesEdit(EventType $eventType){

    }
    public function eventTypesUpdate(Request $request,EventType $eventType){

    }

    public function eventTypeDelete(EventType $eventType){
        $this->setTypeInfo('Delete');
        return view('config.eventtypes.delete')->with('data',$this->data);
    }

    public function eventTypeDestroy(EventType $eventType){
        $eventType->delete();
        return redirect(route('event-type.index'))->with('info','record deleted');
    }

    private function setTypeInfo($action_name){
        $this->data['controller_name'] = 'Event Types';
        $this->data['category_name'] = 'Config';
        $this->data['action_name'] = $action_name;
    }

    public function eventTypesStoreAjax(Request $request){
        $data = $request->validate([
            'name' => 'required|min:5|max:50',
            'code' => 'required|max:8'
        ]);
        $eventType = new EventType();
        $eventType['name'] = $data['name'];
        $eventType['code'] = $data['code'];
        if(isset($request['repeated'])){
            if($request['repeated'] == 'on'){
                $eventType['repeated'] = 1;
                $eventType['period'] = $request['interval'];
            }
        }
        if(isset($request['description']) && strlen($request['description']) > 0){
            $eventType['description'] = $request['description'];
        }
        $eventType->save();

        return response(['message' => 'Event type added'],201);
    }

    public function eventTypesDestroyAjax(Request $request){
        $id = $request['remove_event_type_id'];
        $eventType = EventType::findOrFail($id);
        $eventType->delete();

        return response(['message'=>'Event type removed'],201);
    }

    public function getByIdAjax(Request $request){
        $id = $request['edit_event_type_id'];

        $event_type = EventTypeInfo::findOrFail($id);

        return response()->json(['event_type'=>$event_type],201);
    }

    public function updateAjax(Request $request){
        $data = $request->validate([
            'edit_name' => 'required|min:5|max:50',
            'edit_code' => 'required|max:8'
        ]);
        $eventType = EventType::findOrFail($request['edit_event_type_id']);
        $eventType['name'] = $data['edit_name'];
        $eventType['code'] = $data['edit_name'];
        $eventType['active'] = (isset($request['edit_active']) && strtolower($request['edit_active']) == 'on') ? 1 : 0;
        if(isset($request['edit_repeated']) && $request['edit_repeated']== 'on'){
            $eventType['repeated'] = 1;
            $eventType['period'] = isset($request['edit_interval']) ? $request['edit_interval'] :0 ;
        }
        $eventType['description'] = $request['edit_description'];
        $eventType->save();

        return response(['message'=>'Event type updated'],201);
    }

    public function getEventByIdAjax(Request $request){
        $id = $request['event_id'];
        $event = DB::table('church_events_info')->where('id',$id)->select('*')->get();

        return response(['event'=>$event],201);
    }

    public function registrationSheets(Request $request){
        if($request->ajax()){
            $registration_sheets = RegistrationSheetInfo::select([
                'id',
                'name',
                'last_registration_date',
                'registration_price',
                'registered_members'
            ]);
            return DataTables::of($registration_sheets)
                ->addColumn('actions', function ($row){
                    $membersUrl = route('events.registrationSheetMembers',['registration_sheet' => $row->id]);
                    $deleteUrl = route('events.delete',['event' => $row->id]);
                    $showUrl = route('events.show',['event' => $row->id]);;
                    return
                        "<a class='btn btn-teal btn-sm rounded mr-1' data-id='$row->id' onclick='openEditModal(event)'>
                            <i class='fa fa-edit' data-id='$row->id' onclick='openEditModal(event)'></i>
                         </a>"
                        ."<a class='btn btn-primary btn-sm rounded mr-1' href='$membersUrl'>
                                <i class='fa fa-users'></i>
                          </a>"
                        ."<a class='btn btn-danger btn-sm rounded'
                                    data-id='$row->id' data-name='$row->name' onclick='openRemoveModal(event)'>
                                <i class='fa fa-trash' data-id='$row->id' data-name='$row->name' onclick='openRemoveModal(event)'></i>
                         </a>";

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        $this->data['currencies'] = Currency::all();
        return view('events.registration')->with('data',$this->data);
    }

    /**
     * @param Request $request
     * @param RegistrationSheetInfo $registrationSheet
     * @return Application|Factory|View
     * @throws Exception
     */
    public function registrationSheetInfo(Request $request, RegistrationSheetInfo $registrationSheet){
        if($request->ajax()){
            $members = DB::table('registration_sheet_item_info')
                ->select('id','member','paid_amount','registration_date','currency_code','member_id')
                ->where('sheet_id',$registrationSheet->id);
            return DataTables::of($members)
                ->addColumn('actions', function ($row){
                    return "<a class='btn btn-sm btn-danger rounded' href='#' onclick='openRemoveModal(event)'
                                data-id='$row->member_id' data-name='$row->member'>
                                <i class='fa fa-trash' data-id='$row->member_id' data-name='$row->member'></i>
                             </a>";

                })
                ->addColumn('amount',function($row){
                    return "<span class='font-weight-bold mr-1'>$row->currency_code</span>$row->paid_amount";
                })
                ->rawColumns(['actions','amount'])
                ->make(true);
        }
        $this->data['sheet'] = $registrationSheet;
        $this->data['currencies'] = Currency::all();
        $this->data['sheet_currency'] = Currency::find($registrationSheet->currency_id);
        return view('events.sheet')->with('data',$this->data);
    }

    public function storeSheetItem(Request $request){
        $data = $request->validate([
            'sheet_id' => 'required',
            'member_id' => 'required',
            'registration_date' => 'required',
        ]);
        $data['currency_id'] = isset($request['currency_id'])? $request['currency_id']: null;
        $data['registration_date'] = Carbon::parse($data['registration_date']);
        $data['paid_amount'] = isset($request['paid_amount'])? $request['paid_amount'] : 0.00;
        DB::table('registration_sheet_item')
            ->insert($data);

        return response(['message'=>trans('common.record_stored_label')],201);
    }

    public function removeItemFromSheet(Request $request){
        $data = $request->validate([
           'sheet_id' => 'required',
           'remove_member_id' => 'required'
        ]);
        DB::table('registration_sheet_item')
            ->where('sheet_id',$data['sheet_id'])
            ->where('member_id',$data['remove_member_id'])
            ->delete();

        return response(['message' => trans('common.record_deleted_label')],201);
    }

    public function eventsListJson(Request $request){
        $term = $request['name'];
        //TODO: Only event that dont already have a Registration sheet and have the should register column set to true
        $events = DB::table('church_event_info')
                    ->select('id',DB::raw('title as text'))
                    ->where('title','like',"%$term%")->get();
        return response()->json([
            'results' =>$events
        ]);

    }

    public function getEventByIdJson(Request $request){
        $event_id = $request['event_id'];
        $event = ChurchEventInfo::findOrFail($event_id);
        return response()->json(['event'=>$event],201);
    }

    public function getSheetByIdJson(Request $request){
        $sheet_id = $request['sheet_id'];
        $sheet = RegistrationSheetInfo::findOrFail($sheet_id);
        return response()->json(['sheet'=>$sheet],201);
    }
    public function storeRegistrationSheet(Request $request){
        $data = $request->validate([
           'name' => 'required',
           'last_registration_date' => 'required',
        ]);
        $data['registration_price'] = 0.00 ;
        $data['last_registration_date'] = Carbon::parse($data['last_registration_date']);
        if(isset($request['event_id'])){
            $data['event_id'] = $request['event_id'];
            $data['currency_id'] = $request['currency_id'];
            $data['registration_price'] = isset($request['registration_price']) ? $request['registration_price'] : 0.00 ;

        }
        if($request['registration_fee'] == 'on'){
            $data['currency_id'] = $request['currency_id'];
            $data['registration_price'] = isset($request['registration_price']) ? $request['registration_price'] : 0.00 ;
        }
        if($request['limit_registrations'] == 'on'){
            $data['limit_registrations'] = true;
            $data['max_registrations'] = isset($request['max_registrations']) ? $request['max_registrations'] : 1;
        }

        DB::table('event_registration_sheet')
            ->insert($data);

        return response(['message'=> trans('common.record_stored_label')],201);
    }

    public function updateRegistrationSheet(Request $request){
        $temp = $request->validate([
            'edit_name' => 'required',
            'edit_last_registration_date' => 'required',
            'edit_sheet_id' => 'required'
        ]);
        $data = array();
        $data['registration_price'] = 0.00 ;
        $data['last_registration_date'] = Carbon::parse($temp['edit_last_registration_date']);
        if(isset($request['edit_event_id'])){
            $data['event_id'] = $request['edit_event_id'];
            $data['currency_id'] = $request['edit_currency_id'];
            $data['registration_price'] = isset($request['edit_registration_price']) ? $request['edit_registration_price'] : 0.00 ;

        }
        if($request['edit_registration_fee'] == 'on'){
            $data['currency_id'] = $request['edit_currency_id'];
            $data['registration_price'] = isset($request['edit_registration_price']) ? $request['edit_registration_price'] : 0.00 ;
        }
        if($request['edit_limit_registrations'] == 'on'){
            $data['limit_registrations'] = true;
            $data['max_registrations'] = isset($request['edit_max_registrations']) ? $request['edit_max_registrations'] : 1;
        }

        DB::table('event_registration_sheet')
            ->where('id',$temp['edit_sheet_id'])
            ->update($data);

        return response(['message'=> trans('common.record_stored_label')],201);
    }


    public function destroySheetAjax(Request $request){
        $data = $request->validate([
           'remove_sheet_id' => 'required'
        ]);

        DB::transaction(function() use($data){
            DB::table('registration_sheet_item')
                ->where('sheet_id',$data['remove_sheet_id'])
                ->delete();
            DB::table('event_registration_sheet')
                ->where('id',$data['remove_sheet_id'])
                ->delete();
        });

        return response(['message'=>trans('common.record_deleted_label')],201);
    }
    public function getTotalAmountOnSheet(Request $request){
        $sheet_id = $request->validate([
            'sheet_id' => 'required|min:1'
        ]);
        //TODO: Get Sum of amount paid amount for this sheet
        $result = DB::table('registration_sheet_item_info')
                     ->where('sheet_id',$sheet_id)
                     ->sum('paid_amount');
        $num_members = DB::table('registration_sheet_item_info')
            ->where('sheet_id',$sheet_id)
            ->count();
        return response()->json(['amount'=>$result,'num_members' => $num_members],201);
    }

    public function exportRegistrationSheet(Request $request){
        $sheet_id = $request['sheet_id'];
        //dd($sheet);

        $temp = DB::table('registration_sheet_info as sheet')
            ->leftJoin('currencies','sheet.currency_id','=','currencies.id')
            ->where('sheet.id',$sheet_id)
            ->select('sheet.id as id','sheet.name as name','currencies.code as currency','last_registration_date','registered_members')->get()->first();
        $data['sheet_name'] = $temp->name;
        $data['currency'] = $temp->currency;
        $data['sheet_name'] = $temp->name;
        $data['generated_date'] = Carbon::now()->toDateTimeString();
        $data['last_date'] = $temp->last_registration_date;
        $data['generated_by'] = auth()->user()->name;
        $data['total_amount'] = DB::table('registration_sheet_item_info')
            ->where('sheet_id',$sheet_id)
            ->sum('paid_amount');
        $this->data['num_members'] = $temp->registered_members;
        $name = $data['sheet_name'] ." export.xlsx";
        //dd($data);
        return Excel::download(new RegistrationSheetExport($sheet_id,$data),$name);
    }

    public function membersNotOnSheet(Request $request){
        $sheetId = $request['sheet_id'];
        $term = $request['term'];
        //Check if members present for this group and sheet id
        $membersPresent = DB::table('registration_sheet_item_info')
            ->where('sheet_id',$sheetId)
            ->select('member_id as id')->get()->toArray();

        //if no members present on sheet return full member list
        if(count($membersPresent) == 0){
            $results = DB::table('member_info')
                ->where('name','like',"%$term%")
                ->select('id',DB::raw('name as text'))
                ->orderBy('name','asc')
                ->get();
            return response()->json(['results'=>$results]);
        }
        $temp = array();
        foreach ($membersPresent as $member){
            array_push($temp,$member->id);
        }
        $membersPresent = $temp;
        //dd($membersPresent);
        // if members present on sheet return list of members not present on sheet
        $results = DB::table('member_info')
            ->where('name','like',"%$term%")
            ->select('id',DB::raw('name as text'))
            ->whereNotIn('id',$membersPresent)
            ->get();
        return response()->json(['results'=>$results]);
    }
}
