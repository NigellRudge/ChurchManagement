<?php

namespace App\Http\Controllers;

use App\Exports\ConvertExport;
use App\Exports\CovidRegistrationExport;
use App\Models\CovidSheetInfo;
use App\Models\MemberInfo;
use App\Services\CovidRegistrationService;
use App\utils\CustomUtils;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class RegistrationController extends CommonController
{
    private $covidService;
    public function __construct(CovidRegistrationService $service)
    {
        parent::__construct();
        $this->covidService = $service;
        $this->data['controller_name'] = 'Registrations';
        $this->data['action_name'] = 'index';
        $this->data['category_name'] = 'Members';
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){
        if($request->ajax()){
            return DataTables::of($this->covidService->getSheets())
                ->addColumn('actions', function ($row){
                    $membersUrl = route('covid-registration.sheetInfo',['sheet' => $row->id]);
//                    $date = CustomUtils::parseDate($row->date);
                    return
                        "<a class='btn-teal btn btn-sm rounded text-white  font-weight-bold mr-1'
                                data-id='$row->id' data-name='$row->name' data-date='$row->date' onclick='openEditModal(event)'>
                                <i class='fa fa-edit'  data-id='$row->id' data-name='$row->name' data-date='$row->date'></i>
                         </a>"
                        ."<a class='btn-primary btn btn-sm rounded text-white  font-weight-bold mr-1' href='$membersUrl'>
                                <i class='fa fa-users'></i>
                         </a>"
                        ."<a class='btn-danger btn btn-sm rounded text-white  font-weight-bold mr-1'
                            data-id='$row->id' data-name='$row->name' data-date='$row->date' onclick='openRemoveModal(event)'>
                            <i class='fa fa-trash' data-id='$row->id' data-name='$row->name' data-date='$row->date'></i>
                         </a>";

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('registration.index')->with('data',$this->data);
    }

    public function storeSheet(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'date' => 'required',
        ]);
        $data['created_by'] = auth()->user()->id;
        $data['date'] = Carbon::parse($data['date'])->toDateString();
        $result = $this->covidService->AddOrUpdateSheet($data);
        return response(['message'=>'Sheet Saved'],201);
    }

    public function updateSheet(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'date' => 'required',
        ]);
        $data['date'] = Carbon::parse($data['date'])->toDateString();
        $result = $this->covidService->AddOrUpdateSheet($data,$request['edit_sheet_id']);
        return response(['message'=>'Sheet Saved'],201);
    }

    public function destroySheet(Request $request){
        $data = $request->validate(['remove_sheet_id' => 'required']);
        $result = $this->covidService->destroySheet($data['remove_sheet_id']);
        return response(['message'=> 'Sheet Removed'],201);
    }

    /**
     * @param Request $request
     * @param CovidSheetInfo $sheet
     * @return Application|Factory|View
     * @throws Exception
     */
    public function sheetInfo(Request $request, CovidSheetInfo $sheet){
        if($request->ajax()){
            return DataTables::of($this->covidService->membersOnSheet($sheet->id,null))
                ->addColumn('actions', function ($row){
                    return "<a class='btn btn-sm btn-danger rounded' href='#' onclick='openRemoveModal(event)'  data-id='$row->member_id' data-name='$row->member'>
                                <i class='fa fa-trash' data-id='$row->member_id' data-name='$row->member'></i>
                             </a>";
                })
                ->addColumn('image_info', function($row){
                    return $this->getMemberImage($row->member_id);
                })
                ->addColumn('member_info',function($row){
                    $img = $this->getMemberImage($row->member_id,false);;
                    $image = "<img alt='member_image' src='$img' style='object-fit: cover;border-radius: 30px' width='50' height='50' />";
                    $nameContainer = "<div class='d-flex flex-column px-2 py-1'>
                                        <span class='font-weight-bold text-dark'>$row->member</span>
                                        <span class='font-weight-normal' style='font-size: 0.90rem;margin-top: 2px'>$row->member_type</span>
                                    </div>";
                    return "<div class='d-flex flex-row'>
                                $image
                                $nameContainer
                            </div>";
                })
                ->rawColumns(['actions','amount', 'image_info','member_info'])
                ->make(true);
        }
        $this->data['sheet'] = $sheet;
        $this->data['show_export'] = $sheet->registered_members > 0;
        return view('registration.show')->with('data',$this->data);
    }

    public function removeFromSheet(Request $request,CovidSheetInfo $sheet){
        $data = $request->validate([
            'member_id' => 'required'
        ]);
        $result = $this->covidService->removeFromSheet($sheet->id,$data['member_id']);
        return response(['message'=>trans('common.record_stored_label')],200);
    }

    public function addToSheet(Request $request, CovidSheetInfo $sheet){
        $data = $request->validate([
            'member_id' => 'required'
        ]);
        $result = $this->covidService->addToSheet($sheet->id, $data['member_id']);
        return response(['message'=>trans('common.record_stored_label')],200);
    }

    public function getMembersNotOnSheet(Request $request){
        $name = $request['name'];
        $page = $request['page'] ?? null;
        $sheetId = $request['sheet_id'];
        $results = $this->covidService->membersNotOnSheet($sheetId,$name,$page);
        return response()->json([
            'results' => $results['data'],
            'total_items' => $results['count']
        ]);
    }

    public function exportSheet(Request $request){
        $sheetId = $request['sheet_id'];
        $name = DB::table('covid_registration_sheet')->where('id','=',$sheetId)->first()->name;
        $name = trans('common.covid_reg_sheets_label') . '_' . $name;
        return Excel::download(new CovidRegistrationExport($name,$sheetId),$name . '.xlsx');
    }

}
