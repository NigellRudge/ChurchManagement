<?php

namespace App\Http\Controllers;

use App\Models\MemberInfo;
use App\Models\WorkGroup;
use App\Models\WorkGroupInfo;
use App\Models\WorkGroupMemberInfo;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;

class WorkGroupController extends CommonController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['controller_name'] = "Groups";
        $this->data['action_name'] = "Index";
        $this->data['category_name'] = "Workers";
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
            $groups = DB::table('work_group_info')
                        ->select([
                            'id','name','pastor','coordinator','active_members'
                        ]);
            return DataTables::of($groups)
                ->addColumn('actions', function ($row){
                    $editUrl = route('work-groups.info',['work_group' => $row->id]);
                    return
                        "<a class='btn btn-primary rounded btn-xs text-white font-weight-bold mr-1' href='$editUrl' style='cursor:pointer'>
                            <i class='fa fa-users'></i>
                         </a>"
                        ."<a class='btn-teal btn btn-xs rounded text-white  font-weight-bold mr-1' onclick='openEditModal(event)'  data-id='$row->id' style='cursor:pointer'>
                            <i class='fa fa-edit ' data-id='$row->id'></i>
                         </a>"
                         ."<a class='btn btn-danger rounded btn-xs text-white font-weight-bold mr-1' onclick='openRemoveModal(event)'  data-id='$row->id' data-name='$row->name' style='cursor:pointer'>
                             <i class='fa fa-trash' data-id='$row->id' data-name='$row->name'></i>
                          </a>";

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('groups.index')->with('data',$this->data);
    }


    public function destroy(Request $request)
    {
       $id = $request['remove_work_group_id'];
       $group = WorkGroup::findOrFail($id);
       $group->delete();

       return response(['message'=>'Work group removed'],201);
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required|min:6|max:50',
            'pastor_id' => 'required',
            'coordinator_id' => 'required'
        ]);
        $group = WorkGroup::create($data);
        return response(['message'=> 'Work group saved'],201);
    }

    /**
     * @param Request $request
     * @param WorkGroupInfo $workGroup
     * @return Application|Factory|View
     * @throws Exception
     */
    public function info(Request $request, WorkGroupInfo $workGroup){
        if($request->ajax()){
            $members = WorkGroupMemberInfo::where('work_group_id','=',$workGroup->id)
                        ->select('id','member','status','phone_number','status', 'member_id','image','member_type','id_number');
            return DataTables::of($members)
                ->addColumn('actions', function ($row){
                    return "<a class='btn btn-danger btn-sm rounded' onclick='openRemoveModal(event)' data-id='$row->id' data-name='$row->member' style='cursor:pointer'>
                                <i class='fa fa-trash' data-id='$row->id' data-name='$row->member' style='cursor:pointer'></i>
                            </a>";

                })
                ->addColumn('image_info', function($row){
                    $memberInfo = MemberInfo::find($row->member_id);
                    $image = $memberInfo->memberImage();
                    return "<img alt='member_image' src='$image' style='object-fit: cover;border-radius: 12px' width='60' height='60' />";
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
                ->rawColumns(['actions','image_info','member_info'])
                ->make(true);

        }
        $this->data['group'] = $workGroup;
        $this->data['coordinator'] = MemberInfo::find($workGroup->coordinator_id);
        $this->data['pastor'] = MemberInfo::find($workGroup->pastor_id);
       // dd($this->data);
        return view('groups.info')->with('data',$this->data);
    }

    public function getGroupMembers(WorkGroup $workGroup){
        return  DB::table('members')
            ->leftJoin('eagle_memberships as em','members.id','=','em.member_id')
            ->leftJoin('eagle_groups as group','em.group_id','=','group.id')
            ->where('group.id','=',$workGroup->id)
            ->select([
                'members.id as id',
                DB::raw("CONCAT(members.first_name,' ',members.last_name) AS 'name'"),
                'members.phone_number',
                'members.email'
            ])->get();
        //dd($members);
    }

    public function addGroupMemberAjax(Request $request){

        $data = $request->validate([
            'group_id' => 'required',
            'member_id' => 'required',
            'join_date' => 'required'
        ]);
        DB::table('work_group_memberships')
            ->insert([
                'group_id' => $data['group_id'],
                'member_id' => $data['member_id'],
                'join_date' => Carbon::parse($data['join_date'] )->toDateString()
            ]);
        return response(['message'=>trans('common.record_stored_label')],201);

    }

    public function getNotYetMembersJson(Request $request){
        //Get captain id
        $pastor_id = $request['pastor_id'];
        $page = $request['page'];
        $coordinator_id = $request['coordinator_id'];
        $term = $request['name'];
        // get group id
        $group_id = $request['group_id'];

        $resultCount = 10;
        $offset = ($page-1) * $resultCount;

        $active_member_ids = DB::table('work_group_member_info')
            ->where('work_group_id','=',$group_id)
            ->select('id')->get()->toArray();
        $ids = array();

        if(count($active_member_ids) == 0){
            $results = DB::table('member_info')
                ->select(['id','name as text'])
                ->where('name', 'like', "%$term%");
            if($page != null){
                $results->skip($offset)->take($resultCount);
            }
            return response()->json([
                'results' => $results->get(),
                'total_items' => $results->count()
            ]);
        }
        foreach ($active_member_ids as $active_member_id){
            array_push($ids,$active_member_id->id);
        }
        array_push($ids,$pastor_id);
        array_push($ids,$coordinator_id);
        //return response(['active members'=> $active_member_ids]);

        $results = DB::table('member_info')
            ->select(['id','name as text'])
            ->whereNotIn('id',$ids)
            ->where('name', 'like', "%$term%");
        if($page != null){
            $results->skip($offset)->take($resultCount);
        }
        return response()->json([
            'results' => $results->get(),
            'total_items' => $results->count()
        ]);
    }

    public function removeMemberAjax(Request $request){
        $data = $request->validate([
            'group_id' => 'required',
            'remove_member_id' => 'required'
        ]);

        DB::table('work_group_memberships')
            ->where('group_id','=',$data['group_id'])
            ->where('member_id','=',$data['remove_member_id'])
            ->delete();

        return response(['message'=>trans('common.record_deleted_label')],201);
    }

    public function getById(Request $request){
        $group = WorkGroupInfo::findOrFail($request['group_id']);
        return response(['group'=>$group],201);
    }

    public function update(Request $request){
        $data = $request->validate([
            'edit_group' => 'required',
            'edit_name' => 'required',
            'edit_coordinator_id' => 'required',
            'edit_pastor_id' => 'required',
        ]);
        $group = WorkGroup::findOrFail($data['edit_group']);
        $group['name'] = $data['edit_name'];
        $group['coordinator_id'] = $data['edit_coordinator_id'];
        $group['pastor_id'] = $data['edit_pastor_id'];
        $group->save();
        return response(['message'=>'Work Group updated successfully'],201);
    }

    public function workGroupList(Request $request){
        $term = $request['name'];
        $page = isset($request['page']) ? $request['page']: null;
        $resultCount = 10;
        $offset = ($page-1) * $resultCount;

        $results = WorkGroup::select(['id','name as text'])
                ->where('name', 'like', "%$term%");
        $total_items = $results->count();
        if($page != null){
            $results->skip($offset)->take($resultCount);
        }
        return response()->json([
            'results'=>$results->get(),
            'total_items' =>$total_items
        ]);
    }
    public function memberList(Request $request){

    }
}
