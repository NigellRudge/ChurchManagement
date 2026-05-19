<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceSheetExport;
use App\Exports\InfantDedicationExport;
use App\Models\MemberInfo;
use App\Services\MemberService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class DedicationController extends CommonController
{
    private $service;
    public function __construct(MemberService $service)
    {
        parent::__construct();
        $this->data['category_name'] = 'Members';
        $this->data['controller_name'] = 'infant dedication';
        $this->service = $service;
    }


    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){
        if($request->ajax()){
            return DataTables::of($this->service->getDedicatedInfants($request->all()))
                ->addColumn('actions', function ($row){
                    return "<a class='btn btn-primary rounded btn-sm text-white font-weight-bold mr-1' data-id='$row->id' onclick='openDetailModal(event)'>
                                <i class='fa fa-eye' data-id='$row->id'></i>
                            </a>"
                        ."<a class='btn-teal btn btn-sm rounded text-white  font-weight-bold mr-1' onclick='openEditModal(event)' data-id='$row->id' data-name='$row->name'  href='#'>
                                <i class='fa fa-edit' data-id='$row->id' data-name='$row->name' ></i>
                             </a>"
                        ."<a class='btn btn-danger btn-sm rounded text-white font-weight-bold' onclick='openRemoveModal(event)' data-id='$row->id' data-name='$row->name' >
                                <i class='fa fa-trash' data-id='$row->id' data-name='$row->name' ></i>
                             </a>";

                })
                ->addColumn('image_info', function($row){
                    $image = $row->image();
                    return "<img alt='member_image' src='$image' style='object-fit: cover;border-radius: 30px' width='60' height='60' />";
                })
                ->addColumn('mother_image_info', function($row){
                    $image = $row->motherImage();
                    return "<div class='d-flex flex-column'>
                                <img alt='member_image' src='$image' style='object-fit: cover;border-radius: 12px' width='60' height='60' />
                                <span class='text-sm text-dark' style='font-size: 0.9rem'>$row->mother</span>
                            </div>";
                })
                ->addColumn('father_image_info', function($row){
                    $image = $row->fatherImage();
                    return "<div class='d-flex flex-column'>
                                <img alt='member_image' src='$image' style='object-fit: cover;border-radius: 12px' width='60' height='60' />
                                <span class='text-sm text-dark' style='font-size: 0.9rem'>$row->father</span>
                            </div>";
                })
                ->addColumn('gender_info', function($row){
                    $icon = $row->gender_id == 1 ? 'fa fa-male'  : 'fa fa-female' ;
                    $colorStyle = $row->gender_id == 1 ? '#0303fc'  : '#fc035e' ;
                    $value = $row->gender_id == 1? trans('common.gender_male_label'): trans('common.gender_female_label');
                    return "<div class='d-flex flex-row align-items-center justify-content-center'>
                                    <i class='$icon mr-1' style='color: $colorStyle;font-size: 18px'></i>
                                <span class='text-sm text-dark' style='font-size: 0.9rem'>$value</span>
                            </div>";
                })
                ->rawColumns(['actions','gender_info','mother_image_info','father_image_info','image_info'])
                ->make(true);
        }
        return view('dedication.index')->with('data',$this->data);
    }

    public function store(Request $request){
        $data = $request->validate([
           'infant_id' => 'required',
           'dedication_date' => 'required'
        ]);
        $result = $this->service->dedicateInfant($data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function update(Request $request){
        $data = $request->validate([
            'id' => 'required',
            'dedication_date' => 'required'
        ]);
        $result = $this->service->updateDedicatedInfant($data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function destroy(Request $request){
        $id = $request['infant_id'];
        $result = $this->service->removeDedicatedInfant($id);
        if($result){
            return response()->json(['message' => trans('common.record_deleted_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function getById(Request $request){
        $id = $request['id'];
        $item = $this->service->getDedicatedInfantById($id);
        $item->image = $item->image();
        return response()->json(['item' => $item],200);
    }

    public function exportData(Request $request){
        $data['start_date'] = $request['start_date'] ?? Carbon::now()->startOfYear();
        $data['end_date'] = $request['end_date'] ?? Carbon::now()->endOfYear();
        $name = trans('common.infant_dedication_label') . '_' . Carbon::now()->toDateString() . '.xlsx';
        return Excel::download(new InfantDedicationExport($name,$data),$name);
    }

    public function getNotDedicationInfants(Request $request){
        $term = $request['name'];
        $page = $request['page'] ?? null;
        $resultCount = 10;
        $offset = ($page-1) * $resultCount;

        $dedication_infants = DB::table('infant_dedications')->select('infant_id')->get()->toArray();
        $presentIds = array();
        foreach ($dedication_infants as $item){
            array_push($presentIds,$item->infant_id);
        }

        $results = MemberInfo::select(['id','name as text'])
            ->where('name', 'like', "%$term%")
            ->whereNotIn('id',$presentIds)
            ->where('age','<',1);


        $total_items = $results->count();
        if($page != null){
            $results->skip($offset)->take($resultCount);
        }
        return response()->json([
            'results'=>$results->get(),
            'total_items' =>$total_items
        ]);
    }
}
