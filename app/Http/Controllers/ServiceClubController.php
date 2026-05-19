<?php

namespace App\Http\Controllers;

use App\Exports\ServiceClubExport;
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


class ServiceClubController extends CommonController
{
    private $memberService;
    public function __construct(MemberService $service)
    {
        parent::__construct();
        $this->memberService = $service;
        $this->data['controller_name'] = 'Service club';
        $this->data['category_name'] = 'Members';
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){
        if($request->ajax()){
            return DataTables::of($this->memberService->getServiceClubMembers($request->all()))
                ->addColumn('actions', function ($row){
                    return "<a class='btn btn-primary rounded btn-sm text-white font-weight-bold mr-1' data-image='$row->image' data-id='$row->id' onclick='openDetailModal(event)'>
                                <i class='fa fa-eye' data-id='$row->id'></i>
                            </a>"
                        ."<a class='btn-teal btn btn-sm rounded text-white  font-weight-bold mr-1' data-id='$row->id' onclick='openEditModal(event)' >
                                <i class='fa fa-edit' data-id='$row->id'></i>
                             </a>"
                        . "<a class='btn btn-danger btn-sm rounded text-white font-weight-bold' data-image='$row->image' data-id='$row->id' data-name='$row->name' onclick='openDeleteModal(event)'>
                                <i class='fa fa-trash' data-id='$row->id' data-name='$row->name' data-image='$row->image'></i>
                         </a>";

                })
                ->addColumn('image_info', function($row){
                    $image = $row->image;
                    return "<img alt='member_image' src='$image' style='object-fit: cover;border-radius: 12px' width='60' height='60' />";
                })
                ->addColumn('name_info',function($row){
                    //$img = $row->image;
                    $image = "<img alt='member_image' src='$row->image' style='object-fit: cover;border-radius: 30px' width='50' height='50' />";
                    $nameContainer = "<div class='d-flex flex-column px-2 py-1'>
                                        <span class='font-weight-bold text-dark'>$row->name</span>
                                        <span class='font-weight-normal' style='font-size: 0.90rem;margin-top: 2px'>$row->member_type</span>
                                    </div>";
                    return "<div class='d-flex flex-row'>
                                $image
                                $nameContainer
                            </div>";
                })
                ->addColumn('gender_info', function($row){
                    $icon = $row->gender_id == 1 ? 'fa fa-male'  : 'fa fa-female' ;
                    $colorStyle = $row->gender_id == 1 ? '#0303fc'  : '#fc035e' ;
                    $value = $row->gender;
                    return "<span><i class='$icon mr-1' style='color: $colorStyle;font-size: 18px'></i>$value</span>";
                })
                ->rawColumns(['actions','status_info', 'image_info','gender_info','name_info'])
                ->make(true);
        }
        return view('serviceclub.index')->with('data',$this->data);
    }

    public function store(Request $request){
        $data = $request->validate([
           'member_id' => 'required',
           'profession' => 'required',
        ]);
        $data['skills'] = isset($request['skills']) ? $request['skills'] : null;
        $data['business_owner'] = isset($request['business_owner']) ? $request['business_owner'] : null;
        $data['business_name'] = isset($request['business_name']) ? $request['business_name'] : null;
        $data['sectors'] = isset($request['sectors']) ? $request['sectors'] : [];

        $result = $this->memberService->storeServiceClubMember($data);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label')],200);
        }
        return response()->json(['message'=> trans('common.general_error')],500);
    }

    public function update(Request $request){
        $data = $request->validate([
            'member_id' => 'required',
            'profession' => 'required',
        ]);
        $data['skills'] = isset($request['skills']) ? $request['skills'] : null;
        $data['business_owner'] = isset($request['business_owner']) ? $request['business_owner'] : null;
        $data['business_name'] = isset($request['business_name']) ? $request['business_name'] : null;
        $data['sectors'] = isset($request['sectors']) ? $request['sectors'] : [];

        $result = $this->memberService->updateServiceClubMember($data);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label')],200);
        }
        return response()->json(['message'=> trans('common.general_error')],500);
    }

    public function destroy(Request $request){
        $id = $request->validate([
            'service_member_id' => 'required'
        ]);['service_member_id'];
        $result = $this->memberService->removeServiceClubMember($id);
        if($result){
            return response()->json(['message'=> trans('common.record_deleted_label')],200);
        }
        return response()->json(['message'=> trans('common.general_error')],500);
    }

    public function getById(Request $request){
        $id = $request['service_member_id'];
        $data = $this->memberService->getServiceMemberById($id);
        return response()->json(['member' => $data['member'], 'sectors' => $data['sectors']],200);
    }

    public function getSectors(Request $request){
        $term = $request['term'];
        $page = $request['page'] ?? null;
        $resultCount = 10;
        $offset = ($page-1) * $resultCount;

        $results = DB::table('business_sectors')->select('id',DB::raw('name as text'));
        $total_items = $results->count();
        if($page != null){
            $results->skip($offset)->take($resultCount);
        }
        return response()->json([
            'results'=>$results->get(),
            'total_items' =>$total_items
        ]);
    }

    public function export(Request $request){
        $data = $request->all();
        $name = trans('common.service_club_members_label') . '_export_' . Carbon::now()->toDateTimeString();
        $fileName = $name . '.xlsx';
        return Excel::download(new ServiceClubExport($name,$data),$fileName);
    }

    public function getMemberSectors(Request $request){
        $memberId = $request['member_id'];
        $sectors = DB::table('service_member_sectors_info')
            ->where('member_id','=',$memberId)
            ->select('id','sector')->get();
        return response()->json(['sectors' =>$sectors],201);

    }
}
