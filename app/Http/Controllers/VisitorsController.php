<?php

namespace App\Http\Controllers;

use App\Exports\VisitorsSheetExport;
use App\Models\Gender;
use App\Models\VisitorSheetInfo;
use App\Services\VisitorService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class VisitorsController extends CommonController
{
    private $visitorService;
    public function __construct(VisitorService $visitorService)
    {
        parent::__construct();
        $this->visitorService = $visitorService;
        $this->data['controller_name'] = 'Visitors';
        $this->data['action_name'] = 'Index';
        $this->data['category_name'] = 'joshua warriors';
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){
        if($request->ajax()){
            return DataTables::of($this->visitorService->getSheets())
                ->addColumn('actions', function ($row){
                    $url =  route('visitors.sheetInfo',['sheet' => $row->id]);
                    return "<a onclick='openEditModal(event)' class='bg-teal btn btn-sm rounded text-white  font-weight-bold mr-1'
                                data-id='$row->id' style='cursor:pointer'>
                                <i class='fa fa-edit' data-id='$row->id'></i>
                            </a>"
                        . "<a href='$url' class='btn-primary btn btn-sm rounded text-white  font-weight-bold mr-1'
                            data-id='$row->id' style='cursor:pointer'>
                            <i class='fa fa-users' data-id='$row->id'></i>
                          </a>"
                        ."<a onclick='openRemoveModal(event)' class='btn-danger btn btn-sm rounded text-white  font-weight-bold mr-1'
                          data-id='$row->id' data-name='$row->name' style='cursor:pointer'>
                          <i class='fa fa-trash' data-id='$row->id' data-name='$row->name'></i>
                         </a>";

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('eagle.visitors.index')->with('data',$this->data);
    }

    public function storeSheet(Request $request){
        $data = $request->validate([
            'date' => 'required',
            'name' => 'required'
        ]);
        $result = $this->visitorService->storeSheet($data);
        if($result != null){
            return response(['message'=>trans('common.record_stored_label')],201);
        }
        return response(['message'=>trans('common.general_error')],401);
    }

    public function destroySheet(Request $request){
        $data = $request->validate([
            'remove_sheet_id' => 'required'
        ]);
        $result = $this->visitorService->destroySheet($data['remove_sheet_id']);
        return response(['message'=>'sheet removed'],201);

    }

    public function updateSheet(Request $request){
        $data = $request->validate([
            'date' => 'required',
            'name' => 'required'
        ]);
        $result = $this->visitorService->updateSheet($request['sheet_id'],$data);
        if($result){
            return response(['message'=>trans('common.record_stored_label')],201);
        }
        return response(['message'=>trans('common.general_error')],401);
    }

    /**
     * @param Request $request
     * @param VisitorSheetInfo $sheet
     * @return Application|Factory|View
     * @throws Exception
     */
    public function sheetInfo(Request $request, VisitorSheetInfo $sheet){
        if($request->ajax()){
            return DataTables::of($this->visitorService->getSheetContent($sheet['id'],$request->all()))
                ->addColumn('actions', function ($row){
                    return "<a class='bg-teal btn btn-sm rounded text-white  font-weight-bold mr-1' href='#' onclick='openEditModal(event)'
                                data-id='$row->id'>
                                <i class='fa fa-edit'  data-id='$row->id'></i>
                             </a>"
                        ."<a class='btn-danger btn btn-sm rounded text-white  font-weight-bold mr-1' href='#' onclick='openRemoveModal(event)'
                             data-id='$row->id' data-name='$row->name'>
                                <i class='fa fa-trash' data-id='$row->id' data-name='$row->name'></i>
                        </a>";
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        $this->data['sheet'] = $sheet;
        $this->data['genders'] = Gender::all();
        return view('eagle.visitors.sheetInfo')->with('data',$this->data);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function storeVisitor(Request $request){
        $data = $request->validate([
            'sheet_id' => 'required',
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'gender_id' => 'required',
            'invited_by_id' => 'required',
        ]);
        $data['phone_number'] = $request['phone_number'] ?? null;
        $result = $this->visitorService->addToSheet($data);
        return response([
            'message' => trans('common.record_stored_label'),
            'visitor' => $result
        ],200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getVisitorById(Request $request){
        $data = $request->validate([
            'sheet_id' => 'required',
            'edit_visitor_id' => 'required'
        ]);
        $visitor = $this->visitorService->getVisitorInfo($data);
        if($visitor != null){
            return response()->json(['visitor'=>$visitor],201);
        }
        return response()->json(['message'=>trans('common.general_error')],401);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function updateVisitor(Request $request){
        $data = $request->validate([
            'edit_visitor_id' => 'required',
            'edit_first_name' => 'required|min:3|max:50',
            'edit_last_name' => 'required|min:3|max:50',
            'edit_gender_id' => 'required',
            'edit_invited_by_id' => 'required'
        ]);
        $result = $this->visitorService->updateVisitor($request['sheet_id'],$data);
        if($result != null){
            return response(['message'=>trans('common.record_stored_label'),'result'=>$result],201);
        }
        return response(['message'=>trans('common.general_error'),'result'=>$result],401);
    }

    public function destroyVisitor(Request $request){
        $data = $request->validate([
            'sheet_id' => 'required',
            'remove_visitor_id' => 'required'
        ]);
        $result = $this->visitorService->removeFromSheet($data);
        if($result != null){
            return response(['message'=>trans('common.record_deleted_label')],200);
        }
        return response(['message'=>trans('common.general_error')],401);
    }

    public function getSheetById(Request $request){
        $sheet_id = $request['sheet_id'];
        $sheet = $this->visitorService->getSheetInfo($sheet_id);
        return response()->json(['sheet' =>$sheet],201);
    }

    public function getDates(Request $request){
        $results = $this->visitorService->getAvailableDates();
        return response()->json(
            ['results' =>$results]
        );
    }

    public function exportSheet(Request $request){
        $sheet_id = $request['sheet_id'];
        $data = $this->visitorService->getSheetExportData($sheet_id);
        $name = $data['sheet_name'] . "export.xlsx";
        return Excel::download(new VisitorsSheetExport($sheet_id,$data),$name);
    }
}
