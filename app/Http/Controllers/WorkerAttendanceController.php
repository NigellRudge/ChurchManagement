<?php

namespace App\Http\Controllers;

use App\Models\WorkerAttendanceSheet;
use App\Services\WorkerService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WorkerAttendanceController extends CommonController
{

    private $workerService;
    public function __construct(WorkerService $service)
    {
        parent::__construct();
        $this->data['category_name'] = 'workers';
        $this->data['controller_name'] = 'worker attendance';
        $this->workerService = $service;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){

        if($request->ajax()){
            return DataTables::of($this->workerService->getAttendanceSheets())
                ->addColumn('actions', function ($row){
                    $editUrl = route('workerAttendance.show',['sheet' => $row->id]);
                    return
                        "<a class='btn btn-primary rounded btn-sm text-white font-weight-bold mr-1' href='$editUrl' style='cursor:pointer'>
                            <i class='fa fa-users'></i>
                         </a>"
                        ."<a class='btn-success btn btn-sm rounded text-white font-weight-bold mr-1' onclick='editSheet(event)'  data-id='$row->id' style='cursor:pointer'>
                            <i class='fa fa-edit' data-id='$row->id'></i>
                         </a>"
                        ."<a class='btn btn-danger rounded btn-sm text-white font-weight-bold mr-1' onclick='removeSheet(event)'  data-id='$row->id' data-name='$row->name' style='cursor:pointer'>
                             <i class='fa fa-trash' data-id='$row->id' data-name='$row->name'></i>
                          </a>";

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('workerAttendance.index')->with('data', $this->data);
    }

    public function storeSheet(Request $request){
        $data = $request->validate([
            'group_id' => 'required',
            'name' => 'required',
            'date' => 'required'
        ]);
        $result = $this->workerService->storeSheet($data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function destroySheet(Request $request){
        $sheetId = $request->validate(['sheet_id' => 'required'])['sheet_id'];
        $result = $this->workerService->deleteSheet($sheetId);
        if($result){
            return response()->json(['message' => trans('common.record_deleted_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function editSheet(Request $request){
        $data = $request->validate([
            'group_id' => 'required',
            'name' => 'required',
            'date' => 'required'
        ]);
        $result = $this->workerService->updateSheet($request['sheet_id'], $data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function getSheetById(Request $request){
        $sheetId = $request['id'];
        $sheet = WorkerAttendanceSheet::find($sheetId);
        return response()->json(['sheet'=>$sheet],201);
    }

    /**
     * @param Request $request
     * @param WorkerAttendanceSheet $sheet
     * @return Application|Factory|View
     * @throws Exception
     */
    public function viewSheet(Request $request, WorkerAttendanceSheet $sheet){
        if($request->ajax()){
            return DataTables::of($this->workerService->getSheetInfo($sheet->id))
                ->addColumn('actions', function ($row){
                    return "<a class='btn btn-danger rounded btn-sm text-white font-weight-bold mr-1' onclick='removeItem(event)'  data-id='$row->member_id' data-name='$row->member' style='cursor:pointer'>
                             <i class='fa fa-trash' data-id='$row->member_id' data-name='$row->member'></i>
                          </a>";

                })
                ->addColumn('image_info', function($row){
                    $image = $row->memberImage();
                    return "<img alt='member_image' src='$image' style='object-fit: cover;border-radius: 12px' width='60' height='60' />";
                })
                ->addColumn('member_info',function($row){
                    $image = "<img alt='member_image' src='$row->member_image' style='object-fit: cover;border-radius: 30px' width='50' height='50' />";
                    $nameContainer = "<div class='d-flex flex-column px-2 py-1'>
                                        <span class='font-weight-bold text-dark'>$row->member</span>
                                        <span class='font-weight-normal' style='font-size: 0.90rem;margin-top: 2px'>$row->member_type</span>
                                    </div>";
                    return "<div class='d-flex flex-row'>
                                $image
                                $nameContainer
                            </div>";
                })
                ->rawColumns(['actions','image_info','member_info'])
                ->make(true);
        }
        $this->data['sheet'] = $sheet;
        return view('workerAttendance.show')->with('data',$this->data);
    }

    public function addItemToSheet(Request $request,WorkerAttendanceSheet $sheet){
        $member_id = $request['member_id'];
        $result = $this->workerService->addItemToSheet($sheet->id,$member_id);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function removeItemFromSheet(Request $request,WorkerAttendanceSheet $sheet){
        $memberId = $request['member_id'];
        $result= $this->workerService->removeItemFromSheet($sheet->id,$memberId);
        if($result){
            return response()->json(['message' => trans('common.record_deleted_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function getMembersNotOnSheet(Request $request){
        $sheetId = $request['sheet_id'];
        $page = $request['page'];
        $name = $request['name'];
        $groupId = $request['group_id'];
        $data = $this->workerService->getItemsNotOnSheet($sheetId,$page,$name,$groupId);
        return response()->json($data);
    }
}
