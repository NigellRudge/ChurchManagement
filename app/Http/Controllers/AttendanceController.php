<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceSheetExport;
use App\Exports\EagleOverviewExport;
use App\Models\AttendanceSheetInfo;
use App\Models\EagleAttendance;
use App\Models\EagleGroupInfo;
use App\Services\AttendanceService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use function Symfony\Component\String\s;

class AttendanceController extends CommonController
{
    private $attendanceService;
    public function __construct(AttendanceService $service)
    {
        parent::__construct();
        $this->attendanceService = $service;
        $this->data['controller_name'] = 'Attendance';
        $this->data['action_name'] = 'Index';
        $this->data['category_name'] = 'joshua warriors';
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            return DataTables::of($this->attendanceService->getAttendanceSheets())
                ->addColumn('actions', function ($row){
                    $viewUrl = route('attendance.viewSheet',['sheet'=>$row->id]);
                    return "<a class='btn-primary btn btn-sm rounded text-white  font-weight-bold mr-1' href='$viewUrl'>
                                <i class='fa fa-users'></i>
                             </a>"
                        ."<a class='btn-danger btn btn-sm rounded text-white  font-weight-bold mr-1' href='#' onclick='openRemoveModal(event)'
                             data-id='$row->id' data-name='$row->name'>
                             <i class='fa fa-trash'  data-id='$row->id' data-name='$row->name'></i>
                        </a>";

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('attendance.index')->with('data',$this->data);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function storeSheet(Request $request){
        $data = $request->validate([
            'date' => 'required',
            'name' => 'required'
        ]);
        $result = $this->attendanceService->storeSheet($data);
        if($result !== null){
            return response(['message'=>'Attendance sheet stored'],201);
        }
        return response(['message'=>'Something went wrong'],401);
    }

    /**
     * @param Request $request
     * @param AttendanceSheetInfo $sheet
     * @return Application|Factory|View
     * @throws Exception
     */
    public function viewSheet(Request $request, AttendanceSheetInfo $sheet)
    {
        if ($request->ajax()) {
            return DataTables::of($this->attendanceService->getSheetInfo($sheet['id'], $request->all()))
                ->addColumn('actions', function ($row) {
                    $toolTip = trans('common.remove_member_tooltip');
                    return
                        "<a class='btn btn-danger btn-sm rounded ' href='#' onclick='openRemoveModal(event)'
                            data-toggle='tooltip' data-placement='top' title='$toolTip'
                            data-toggle='modal' data-id='$row->id' data-name='$row->member'>
                            <i class='fa fa-trash' data-toggle='modal' data-id='$row->id' data-name='$row->member'></i>
                        </a>";
                })
                ->addColumn('image_info',function($row){
                    return $this->getMemberImage($row->id);
                })
                ->addColumn('member_info',function($row){
                    $image = "<img alt='member_image' src='$row->image' style='object-fit: cover;border-radius: 30px' width='50' height='50' />";
                    $nameContainer = "<div class='d-flex flex-column px-2 py-1'>
                                        <span class='font-weight-bold text-dark'>$row->member</span>
                                        <span class='font-weight-normal' style='font-size: 0.90rem;margin-top: 2px'>$row->member_type</span>
                                    </div>";
                    return "<div class='d-flex flex-row'>
                                $image
                                $nameContainer
                            </div>";
                })
                ->rawColumns(['actions', 'image_info','member_info'])
                ->make(true);
        }
        $this->data['sheet'] = $sheet;
        $this->data['groups'] = EagleGroupInfo::all();
        return view('attendance.sheetinfo')->with('data',$this->data);
    }


    public function destroySheet(Request $request){
        $data = $request->validate([
            'remove_sheet_id' => 'required'
        ]);
        $result = $this->attendanceService->deleteSheet($data['remove_sheet_id']);
        return response(['message'=>'sheet removed'],201);
    }

    public function getAvailableDates(Request $request){
        return response()->json(
            ['results' =>$this->attendanceService->getDates()],201
        );
    }

    public function addToSheet(Request $request){
        $data= $request->validate([
            'member_id' => 'required',
            'sheet_id' => 'required'
        ]);
        $data['group_id'] = DB::table('eagle_member_info')->where('id','=',$data['member_id'])->select('group_id')->get()->first()->group_id;
        $result = $this->attendanceService->addToSheet($data);
        if($result !=  null){
            return response(['message'=>'Item added'],201);
        }
        return response(['message'=>'Something went wrong'],401);
    }

    public function removeFromSheet(Request $request){
        $data = $request->validate([
            'sheet_id' => 'required',
            'remove_member_id' => 'required'
        ]);
        $result = $this->attendanceService->removeFromSheet($data);
        if($result != null){
            return response(['message'=>'Attendee removed'],201);
        }
        return response(['message'=>'Something went wrong'],401);
    }

    public function exportSheet(Request $request){
        $sheet_id = $request['sheet_id'];
        $data = $this->attendanceService->getSheetExportData($sheet_id);
        $name = $data['sheet_name'] . " export.xlsx";
        return Excel::download(new AttendanceSheetExport($sheet_id,$data),$name);
    }
}
