<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceSheetExport;
use App\Exports\EagleMemberOverviewExport;
use App\Exports\EagleOverviewExport;
use App\Exports\VisitorsSheetExport;
use App\Models\AttendanceSheetInfo;
use App\Models\EagleGroup;
use App\Models\EagleGroupInfo;
use App\Models\FirstTimeVisitor;
use App\Models\Gender;
use App\Models\Member;
use App\Models\RollCall;
use App\Models\VisitorSheetInfo;
use App\Services\AttendanceService;
use App\Services\EagleService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;


class EagleGroupController extends CommonController
{
    private $eagleService;
    private $attendanceService;
    public function __construct(EagleService $service,AttendanceService $attendanceService)
    {

        parent::__construct();
        $this->eagleService = $service;
        $this->attendanceService = $attendanceService;
        $this->data['controller_name'] = "Eagle Groups";
        $this->data['category_name'] = 'Joshua Warriors';
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
            return DataTables::of($this->eagleService->getEagleGroups())
                ->addColumn('actions', function ($row){
                    $editUrl = route('eagle-group.edit',['eagle_group' => $row->id]);
                    $removeTooltip = trans('common.remove_item_tooltip');
                    $editTooltip = trans('common.edit_item_tooltip');
                    $viewTooltip = trans('common.view_info_tooltip');
                    return
                        "<a class='btn-teal btn btn-sm rounded text-white  font-weight-bold mr-1' href='#' onclick='openEditModal(event)'
                            data-toggle='tooltip' data-placement='top' title='$editTooltip'
                            data-id='$row->id'>
                            <i class='fa fa-edit' data-id='$row->id'></i>
                          </a>"
                        ."<a class='btn-primary btn btn-sm rounded text-white  font-weight-bold mr-1'
                           data-toggle='tooltip' data-placement='top' title='$viewTooltip'
                            href='$editUrl'>
                            <i class='fa fa-users'></i>
                          </a>"
                        ."<a class='btn-danger btn btn-sm rounded text-white  font-weight-bold mr-1' href='#' onclick='openRemoveModal(event)'
                          data-toggle='tooltip' data-placement='top' title='$removeTooltip'
                          data-toggle='modal' data-id='$row->id' data-name='$row->name'>
                            <i class='fa fa-trash' data-toggle='modal' data-id='$row->id' data-name='$row->name'></i>
                          </a>";
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        $this->data['action_name'] = 'Index';
        $this->data['model_name'] = 'Eagle Group';
        return view('eagle.index')->with('data',$this->data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->data['action_name'] = 'Create';
        return view('eagle.create')->with('data',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:5|max:30',
            'team_captain' => 'required|min:1'
        ]);

        $group = EagleGroup::create($data);
        return redirect()->route('eagle-group.index')->with('success','record stored');
    }

    /**
     * Display the specified resource.
     *
     * @param EagleGroup $eagle_group
     * @return void
     */
    public function show(EagleGroup $eagle_group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param EagleGroupInfo $eagle_group
     * @return Application|Factory|View
     * @throws Exception
     */
    public function edit(Request $request,EagleGroupInfo $eagle_group)
    {
        if($request->ajax()){
            return DataTables::of($this->eagleService->getGroupMembers($eagle_group->id))
                ->addColumn('actions', function ($row){
                    $tooltip = trans('common.remove_member_tooltip');
                    return "<a class='btn btn-danger btn-sm rounded' data-toggle='tooltip' data-placement='top' title='$tooltip' onclick='removeMember(event)' data-id='$row->id' data-name='$row->name'>
                                <i class='fa fa-trash' data-id='$row->id' data-name='$row->name'></i>
                            </a>";
                })
                ->addColumn('image_info', function($row){
                    return $this->getMemberImage($row->id);
                })
                ->addColumn('name_info',function($row){
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
                ->rawColumns(['actions','image_info','name_info','gender_info'])
                ->make(true);
        }
        $this->data['group'] = $eagle_group;
        return view('eagle.edit')->with('data',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param EagleGroup $eagle_group
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request, EagleGroup $eagle_group)
    {
        $data = $request->validate([
            'name' => 'required|min:5|max:30',
            'team_captain' => 'required|min:1'
        ]);

        $eagle_group->update($data);
        return redirect(route('eagle-group.index'))->with('message','Eagle group updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param EagleGroup $eagle_group
     * @return void
     */
    public function destroy(EagleGroup $eagle_group)
    {
        //
    }

    public function delete(EagleGroup $eagle_group)
    {

    }

    public function exportGroupMembers(Request $request){
        $data = $request->validate([
            'group_id' => 'required'
        ]);
        $group = DB::table('eagle_group_info')->where('id','=',$data['group_id'])->select('*')->get()->first();
        $data['generated_date'] = Carbon::now()->toDateTimeString();
        $data['generated_by'] = auth()->user()->name;
        $data['num_members'] = $group->num_members;
        $data['group_name'] = $group->name;
        $data['team_captain'] = $group->team_captain;
        //dd($data);
        $name = $data['group_name'] . 'Members.xlsx';
        return Excel::download(new EagleMemberOverviewExport($data['group_id'],$data),$name);
    }

    public function removeGroupMember(Request $request){
        $group_id = $request['group_id'];
        $member_id = $request['member_id'];
        $this->eagleService->removeMember($group_id,$member_id);
        return response(['message'=>'Member Removed'],201);
     }

    private function getGroupMembers(EagleGroupInfo $eagle_group){
        return  DB::table('eagle_member_info')
                    ->where('group_id',$eagle_group->id)
                    ->select('id',
                        'name',
                        'gender',
                        'phone_number',
                        'email'
                    );
    }

    public function addGroupMemberAjax(Request $request){
        $group = $request['group_id'];
        $member_id = $request['new_member'];
        $this->eagleService->addMember($group,$member_id);
        return response(['message'=>'member Added'],201);
    }

    public function storeAjax(Request $request){
        $data = $request->validate([
            'name' => 'required|min:5|max:30',
            'team_captain' => 'required|min:1'
        ]);
        $this->eagleService->saveEagleGroup($data);
        return response(['message'=>'Eagle group added'],201);
    }

    public function destroyAjax(Request $request){
        $id = $request['remove_group_id'];
        $this->eagleService->deleteGroup($id);
        return response(['message'=>'Eagle group removed successfully'],200);

    }


    public function getNotYetMembersJson(Request $request){
        $captain_id = $request['team_captain'];
        $group_id = $request['group_id'];
        $term = $request['name'];
        $page = $request['page'] ?? null;
        $results = $this->eagleService->getNotYetMembers($group_id,$captain_id,$page,$term);
        return response()->json([
            'results'=>$results->get(),
            'total_items' =>$results->count()
        ]);
    }

    public function getByIdAjax(Request $request){
        $group = EagleGroupInfo::findOrFail($request['group_id']);
        return response()->json(['group'=>$group],200);
    }

    public function updateAjax(Request $request){
        $data = $request->validate([
           'edit_group_id' => 'required',
           'edit_name' => 'required',
           'edit_team_captain' => 'required',
        ]);
        $result = $this->eagleService->updateEagleGroup($data['edit_group_id'],$data);
        return response(['message'=>'Eagle Group updated successfully'],200);
    }

    public function dexportEagleGroupOverview(Request $request){
        $data = [
            'name' => 'Eagle Group Overview',
            'generated_date' => Carbon::now()->toDateTimeString(),
            'num_groups' => EagleGroupInfo::all()->count(),
            'generated_by' => auth()->user()->name
        ];
        $name = 'Eagle_group_overview.xlsx';
        return Excel::download(new EagleOverviewExport($data),$name);
    }

    public function getEagleGroupsJson(Request $request){
        $term = $request['term'];
        $groups = DB::table('eagle_group_info')
                    ->select('id',DB::raw('name as text'))
                    ->where('name','like',"%$term%")->get();

        return response()->json(['results'=>$groups]);
    }

    public function getGroupMembersJson(Request $request){
        $data = $this->attendanceService->getMembers($request['sheet_id'],$request['term'],$request['page']);
        return response()->json($data);
    }

}
