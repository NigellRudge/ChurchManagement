<?php

namespace App\Http\Controllers;

use App\Exports\ConvertExport;
use App\Models\Convert;
use App\Services\MemberService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use function Symfony\Component\Translation\t;

class ConvertController extends CommonController
{
    private $memberService;
   public function __construct(MemberService $service)
   {
       parent::__construct();
       $this->memberService = $service;
   }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){
       if($request->ajax()){
           return DataTables::of( $this->memberService->getConverts($request->all()))
               ->addColumn('actions', function ($row){
                   return
                       "<button class='btn-teal btn btn-sm rounded text-white  font-weight-bold mr-1' onclick='openEditModal(event)'  data-id='$row->id'>
                            <i class='fa fa-edit' data-id='$row->id'></i>
                         </button>"
                       ."<div class='btn-danger btn btn-sm rounded text-white  font-weight-bold mr-1' onclick='openRemoveModal(event)'  data-id='$row->id' data-name='$row->name' >
                                <i class='fa fa-trash' data-id='$row->id' data-name='$row->name' ></i>
                          </div>";
               })
               ->addColumn('gender_info', function($row){
                   $icon = $row->gender_id == 1 ? 'fa fa-male'  : 'fa fa-female' ;
                   $colorStyle = $row->gender_id == 1 ? '#0303fc'  : '#fc035e' ;
                   $value = trans("common.$row->trans_string");
                   return "<span class='d-flex flex-row justify-content-center align-items-center'><i class='$icon mr-2' style='color: $colorStyle;font-size: 16px'></i>$value</span>";
               })
               ->rawColumns(['actions','gender_info'])
               ->make(true);
       }
       $this->data['action_name'] = 'Converts Index';
       $this->data['controller_name'] = 'Members';
       $this->data['category_name'] = 'Members';
       if(auth()->check()){
           $this->data['user'] = auth()->user();
       }
       return view('converts.index')->with('data',$this->data);
   }

    public function store(Request $request){
        $data = $request->validate([
            'first_name' => 'required|min:5|max:40',
            'last_name' => 'required|min:5"max:40',
            'convert_date' => 'required',
            'phone_number' => 'required',
            'district_id' => 'required|min:1',
            'gender_id' => 'required',
            'address' => 'required'
        ]);

        $convert = Convert::create($data);
        $convert->save();

        return response(['message'=>trans('common.record_stored_label')],200);
    }

    public function update(Request $request){
        $id = $request['edit_convert_id'];
        $data = $request->validate([
            'edit_first_name' => 'required|min:5|max:40',
            'edit_last_name' => 'required|min:5"max:40',
            'edit_convert_date' => 'required',
            'edit_phone_number' => 'required',
            'edit_district_id' => 'required|min:1',
            'edit_gender_id' => 'required',
            'edit_address' => 'required'
        ]);
        $convert = Convert::findOrFail($id);
        $convert['first_name'] = $data['edit_first_name'];
        $convert['last_name'] = $data['edit_last_name'];
        $convert['address'] = $data['edit_address'];
        $convert['phone_number'] = $data['edit_phone_number'];
        $convert['district_id'] = $data['edit_district_id'];
        $convert['gender_id'] = $data['edit_gender_id'];
        $convert['convert_date'] = $data['edit_convert_date'];
        $convert->save();
        return response(['message'=>trans('common.record_stored_label')],200);

    }

    public function destroy(Request $request){
        $convertId = $request['remove_convert_id'];
        $convert = Convert::findOrFail($convertId);
        $convert->delete();
        return response(['message'=>trans('common.record_deleted_label')],200);

    }

    public function getById(Request $request){
        $convertId = $request['edit_convert_id'];
        $convert = Convert::findOrFail($convertId);
        return response()->json(['convert'=>$convert],201);
    }

    public function export(Request $request){
        $data = array();
        $data['from_date'] = isset($request['export_from_date']) ? $request['export_from_date'] : null;
        $data['to_date'] = isset($request['export_to_date']) ? $request['export_to_date'] : null;
        $data['gender'] = isset($request['export_gender']) ? $request['export_gender'] : null;
        $name = trans('common.converts_label'). '_' .Carbon::now()->toDateString();
        return Excel::download(new ConvertExport($name,$data),$name . '.xlsx');
    }
}
