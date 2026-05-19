<?php

namespace App\Http\Controllers;

use App\Exports\BirthDayExport;
use App\Exports\ConvertExport;
use App\Exports\MemberExport;
use App\Imports\MemberImport;
use App\Models\Convert;
use App\Models\Country;
use App\Models\Currency;
use App\Models\District;
use App\Models\Gender;
use App\Models\Member;
use App\Models\MemberInfo;
use App\Models\MemberRelationInfo;
use App\Models\MemberType;
use App\Models\Visitor;
use App\Services\MemberService;
use App\Services\SeedsService;
use App\utils\CustomUtils;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;


class MemberController extends CommonController
{
    private $seedService;
    private $memberService;
    public function __construct(SeedsService $service, MemberService $memberService)
    {
        parent::__construct();
        $this->seedService = $service;
        $this->data['controller_name'] = 'Members';
        $this->data['category_name'] = 'Members';
        $this->memberService = $memberService;
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
            return DataTables::of($this->memberService->getAll($request->all()))
                ->addColumn('actions', function ($row){
                    $editUrl = route('members.edit',['member' => $row->id]);
                    $deleteUrl = route('members.delete',['member' => $row->id]);
                    $showUrl = route('members.show',['member' => $row->id]);
                    $imageUrl = $row->image;
                    $ShowReactivateButton = $row->active != 1 ?
                        "<a class='btn btn-info btn-xs rounded text-white font-weight-bold' data-image='$imageUrl' data-id='$row->id' data-name='$row->name' onclick='openReactivateModal(event)'>
                                <i class='fa fa-door-open' data-id='$row->id' data-name='$row->name' data-image='$imageUrl'></i>
                             </a>"
                        :
                        "<a class='btn btn-danger btn-xs rounded text-white font-weight-bold' data-image='$imageUrl' data-id='$row->id' data-name='$row->name' onclick='openDeleteModal(event)'>
                                <i class='fa fa-trash' data-id='$row->id' data-name='$row->name' data-image='$imageUrl'></i>
                         </a>";
                    return "<a class='btn btn-primary rounded btn-xs text-white font-weight-bold mr-1' href='$showUrl'>
                                <i class='fa fa-eye'></i>
                            </a>"
                            ."<a class='btn-teal btn btn-xs rounded text-white  font-weight-bold mr-1 ' href='$editUrl'>
                                <i class='fa fa-edit '></i>
                             </a>"
                            . $ShowReactivateButton;

                })
                ->addcolumn('status_info', function ($row){
                    $value = $row->active  == 1 ? 'Active' : 'In-active';
                    $status = $row->active == 1 ? $row->active : 0;
                    return $this->getItemStatusColumn($status,$value);
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
                ->rawColumns(['actions','status_info', 'name_info','gender_info'])
                ->make(true);
        }
        $this->data['action_name'] = 'Index';
        $this->data['member_types'] = MemberType::all();
//        $this->data['genders'] = Gender::all();
        if(auth()->check()){
            $this->data['user'] = auth()->user();
        }
        return view('members.index')->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->data['action_name'] = 'Create';
        $this->data['member_types'] = MemberType::all();
        $this->data['genders'] = Gender::all();
        $this->data['districts'] = District::all();
        $this->data['education_types'] = DB::table('education')->select('id','name')->get();
        if(auth()->check()){
            $this->data['user'] = auth()->user();
        }
        return view('members.create')->with('data',$this->data);
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
            'first_name' => 'required|min:3|max:40',
            'last_name' => 'required|min:3|max:40',
            'birth_date' => 'required',
            'address' => 'required|max:60',
            'phone_number' => 'required',
            'member_type_id' => 'required|min:1',
            'district_id' => 'required|min:1',
            'gender_id' => 'required|min:1',
            'image'=> 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $result = $this->memberService->createMember($request, $data);
//        $data['baptized'] = $request['baptized'] == 1;
//        $data['baptize_date'] = $request['baptized'] == 1? $request['baptize_date']:null;
//        $data['email'] = isset($request['email']) ? $request['email'] : null;
//        $data['convert_date'] = isset($request['convert_date']) ? $request['convert_date']: null;
//        $data['skills'] = $request['skills'];
//        $data['notes'] = $request['notes'];
//        $data['maiden_name'] = $request['maiden_name'];
//        $data['neighborhood'] = $request['neighborhood'];
//        $data['education_id'] = $request['education_id'];
//        $data['job_description'] = $request['job_description'];
//        $data['id_number'] = isset($request['id_number']) ? $request['id_number'] : null;
//        $member = Member::create($data);
//        if ($request->hasFile('image')){
//            $file_name = $member->first_name . '-' . $member->last_name . Carbon::now()->toDateString() . '.' . $request->file('image')->getClientOriginalExtension();
//            $destination = 'public/uploads/images/users/';
//            $request->file('image')->storeAs($destination,$file_name);
//            $member->image = $file_name;
//            $member->save();
//        }
        return redirect(route('members.index'))->with('success',trans('common.record_stored_label'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param MemberInfo $member
     * @return Application|Factory|View
     * @throws Exception
     */
    public function show(Request $request, MemberInfo $member)
    {
        if($request->ajax()){
            return DataTables::of($this->seedService->getSeeds(['member_id'=>$member->id]))
                ->addIndexColumn()
                ->addColumn('amount_formatted',function($row){
                    return  $row->currency .  ' $' . number_format($row->amount,2);
                })
                ->addColumn('actions', function ($row) use($member){
                    return "<a class='btn btn-danger btn-sm rounded-lg text-white font-weight-bold mr-1' onclick='openRemoveSeedModal(event)'
                                data-member='$member->name' data-date='$row->date'  data-id='$row->id' data-amount='$row->amount' data-code='$row->currency' data-title='$row->title' style='cursor:pointer'>
                                <i class='fa fa-trash' data-member='$member->name' data-date='$row->date'  data-id='$row->id' data-amount='$row->amount' data-code='$row->currency' data-title='$row->title'></i>
                                </a>"
                        ."<a class='btn btn-teal btn-sm rounded-lg text-white font-weight-bold' onclick='openEditSeedModal(event)'  data-id='$row->id' style='cursor:pointer'>
                                <i class='fa fa-edit'></i>
                                </a>";

                })
                ->addColumn('type_info', function ($row){
                    if($row->type_id == config('constants.SEED_TYPE_TIDE')){
                        return trans('common.seed_type_tide');
                    }
                    return trans('common.seed_type_special_seed');
                })
                ->rawColumns(['newAmount','actions','type_info'])
                ->make(true);
        }
        $this->data['relations'] = DB::table('member_relation_overview')
                                        ->where('member_id',$member->id)
                                        ->select('id','relative_id','name_relative','relation')->get();
        $this->data['relationship_types'] = DB::table('relationship_types')
                                            ->select('id','name','trans_code')->get();
        $this->data['member'] = $member;
        $this->data['currencies'] = Currency::all();
        $this->data['is_active'] = $member->active;
        $this->data['types'] = DB::table('seed_types')->select('id','name')->get();
        return view('members.show')->with('data',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Member $member
     * @return Application|Factory|View
     */
    public function edit(Member $member)
    {
        $this->data['action_name'] = 'Edit';
        $this->data['genders'] = Gender::all();
        $this->data['member_types'] = MemberType::all();
        $this->data['districts'] = District::all();
        $this->data['member'] = $member;
        $this->data['education_types'] = DB::table('education')->select('id','name')->get();
        if(auth()->check()){
            $this->data['user'] = auth()->user();
        }
        //dd($member);
        return view('members.edit')->with('data',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Member $member
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'first_name' => 'required|min:3|max:40',
            'last_name' => 'required|min:3|max:40',
            'birth_date' => 'required',
            'address' => 'required|max:60',
            'phone_number' => 'required',
            'member_type_id' => 'required|min:1',
            'district_id' => 'required|min:1',
            'gender_id' => 'required|min:1',
            'image'=> 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $data['baptized'] = $request['baptized'] == 1;
        $data['baptize_date'] = $request['baptized'] == 1? $request['baptize_date']:null;
        $data['email'] = isset($request['email']) ? $request['email'] : null;
        $data['convert_date'] = isset($request['convert_date']) ? $request['convert_date']: null;
        $data['skills'] = $request['skills'];
        $data['notes'] = $request['notes'];
        $data['maiden_name'] = $request['maiden_name'];
        $data['neighborhood'] = $request['neighborhood'];
        $data['education_id'] = $request['education_id'];
        $data['job_description'] = $request['job_description'];
        $member->update($data);
        if ($request->hasFile('image')){
            $file_name = $member->first_name . '-' . $member->last_name . Carbon::now()->toDateString() . '.' . $request->file('image')->getClientOriginalExtension();
            $destination = 'public/uploads/images/users/';
            if($member->image != null){
                Storage::delete($destination .$member->image);
            }
            $request->file('image')->storeAs($destination,$file_name);
            //dd($request->file('image'));
            $member->image = $file_name;
            $member->save();
        }
        return redirect(route('members.index'))->with('success','record updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $member = Member::find($request['member']);
        $member->deleted_at = now()->toDateTimeString();
        $member->save();
        return response()->json(['message' => trans('common.record_deleted_label')],200);
    }

    public function delete(Member $member){
        $this->data['member'] = $member;
        if(auth()->check()){
            $this->data['user'] = auth()->user();
        }
        return view('members.delete')->with('data',$this->data);
    }


    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function convertsIndex(Request $request){
       if($request->ajax()){
            return DataTables::of( $this->memberService->getConverts($request->all()))
                ->addColumn('actions', function ($row){
                    return
                        "<button class='btn-teal btn btn-sm rounded text-white  font-weight-bold mr-1' onclick='openEditModal(event)'  data-id='$row->id'>
                            <i class='fa fa-edit' data-id='$row->id'></i>
                         </button>"
                        ."<button class='btn-danger btn btn-sm rounded text-white  font-weight-bold mr-1' onclick='openRemoveModal(event)'  data-id='$row->id' data-name='$row->name' >
                                <i class='fa fa-trash' data-id='$row->id' data-name='$row->name' ></i>
                          </button>";
                })
                ->addColumn('gender_info', function($row){
                    $icon = $row->gender_id == 1 ? 'fa fa-male'  : 'fa fa-female' ;
                    $colorStyle = $row->gender_id == 1 ? '#0303fc'  : '#fc035e' ;
                    $value = trans("common.$row->trans_string");
                    return "<span class='d-flex flex-column justify-content-center align-items-center'><i class='$icon mr-1' style='color: $colorStyle;font-size: 16px'></i>$value</span>";
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
        return view('members.convert.index')->with('data',$this->data);
    }


    public function promoteToMember(Convert $convert){
        $this->data['convert'] = $convert;
        $this->data['districts'] = District::all();
        $this->data['member_types'] = MemberType::all();
        //dd($convert);
        return view('members.convert.promote')->with('data',$this->data);
    }

    public function getMembersJson(Request $request){
        $term = $request['name'];
        $member_type_id = $request['member_type_id'] ?? null;
        $page = $request['page'] ?? null;
        $adult = $request['adult'] ?? null;
        $isInfant = $request['infant'];
        $resultCount = 10;
        $offset = ($page-1) * $resultCount;

        $results = MemberInfo::select(['id','name as text'])
            ->where('name', 'like', "%$term%")
            ->orderBY('name');
        if($member_type_id != null){
            $results->where('member_type_id','=',$member_type_id);
        }
        if($adult){
            $results->where('age','>',20);
        }
        if(isset($isInfant)){
            $results->where('age','<',1);
        }
        $total_items = $results->count();
        if($page != null){
            $results->skip($offset)->take($resultCount);
        }
        return response()->json([
            'results'=>$results->get(),
            'total_items' =>$total_items
        ]);
    }

    public function getPastorsJson(Request $request){
        $term = $request['name'];
        $results = DB::table('pastors_info')
            ->select('id',DB::raw('name as text'))
            ->where('name', 'like', "%$term%")
            ->get();

        return response()->json([
            'results'=>$results
        ]);
    }

    public function getMemberByIdJson(Request $request){
        $id = $request['id'];
        $member = MemberInfo::find($id);
        $data = [
            'id' => $member['id'],
            'name' => $member['name'],
            'member_image' => $member->image
        ];
        return response()->json([
            'member' => $data,
        ],200);
    }

    public function convertStoreAjax(Request $request){
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

        return response(['message'=>trans('common.record_stored_label')],201);
    }

    public function convertUpdateAjax(Request $request){
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
        return response(['message'=>'Convert updated successfully'],201);

    }

    public function convertDestroyAjax(Request $request){
        $convertId = $request['remove_convert_id'];
        $convert = Convert::findOrFail($convertId);
        $convert->delete();
        return response(['message'=>trans('common.record_deleted_label')],201);

    }

    public function convertGetByIdAjax(Request $request){
        $convertId = $request['edit_convert_id'];
        $convert = Convert::findOrFail($convertId);
        return response()->json(['convert'=>$convert],201);
    }

    public function storePromotedConvert(Request $request){

    }

    public function exportConverts(Request $request){
        $data = [
            'from_date' => Carbon::parse($request['export_from_date'])->toDateString(),
            'to_date' => Carbon::parse($request['export_to_date'])->toDateString(),
            'gender' => $request['export_gender']
        ];
        $name = trans('common.converts_label'). '_' .Carbon::now()->toDateString() . '.xlsx';
        return Excel::download(new ConvertExport(trans('common.converts_label'),$data),$name);
    }


    public function importMembers(Request $request){
        $file = $request->validate([
            'import_file' => 'required|file'
        ]);
        Excel::import(new MemberImport,$request->file('import_file'));
        return redirect(route('members.index'))->with('message','File uploaded');
    }

    public function storeMemberRelation(Request $request){
        $data = $request->validate([
            'member_id' => 'required',
            'relative_id' => 'required',
            'relation_id' => 'required'
        ]);

        $result = DB::table('member_relation')
                    ->insert([
                        'member_id' => $data['member_id'],
                        'related_member_id' => $data['relative_id'],
                        'relationship_type_id' => $data['relation_id']
                    ]);
        return response(['message'=> 'Relation Saved'],201);
    }

    public function getRelation(Request $request){
        $id = $request['relation_id'];
        $relation = MemberRelationInfo::findOrFail($id);
        return response()->json(['relation' => $relation],201);
    }

    public function getNotRegisteredMembersJson(Request $request){
        $term = $request['name'];
        $sheetId = $request['sheet_id'];
        $page = $request['page'] ?? null;
        $resultCount = 10;
        $offset = ($page-1) * $resultCount;

        //get registered members
        $registeredMembers = DB::table('registration_sheet_item_info')
            ->where('sheet_id',$sheetId)
            ->select('member_id as id')->get()->toArray();


        if(count($registeredMembers) == 0){
            $results = DB::table('member_info')
                ->select('id',DB::raw('name as text'))
                ->get();
            return response()->json(['results'=>$results]);
        }

        $temp = array();
        foreach ($registeredMembers as $member){
            array_push($temp,$member->id);
        }
        $registeredMembers = $temp;
        //dd($membersPresent);
        // if members present on sheet return list of members not present on sheet
        $results = DB::table('member_info')
            ->where('name','like',"%$term%")
            ->select('id',DB::raw('name as text'))
            ->whereNotIn('id',$registeredMembers);


        return response()->json([
            'results'=>$results->get(),
            'total_items' =>$results->count()
        ]);
    }

    /**
     * @param Request $request
     * @param MemberInfo $member
     * @return mixed
     * @throws Exception
     */
    public function family(Request $request, MemberInfo $member){
        if($request->ajax()){
            return DataTables::of($this->memberService->getRelationships($member->id))
                ->addColumn('actions', function ($row){
                    $temp = trans('common' . '.' .$row->trans_code);
                    return "<a class='btn btn-danger btn-sm rounded font-weight-bold ' onclick='removeRelation(event)'
                        data-id='$row->id' data-name='$row->name_relative' data-relation='$temp'  style='cursor:pointer'>
                        <i class='fa fa-trash' data-id='$row->id' data-name='$row->name_relative' data-relation='$temp' ></i>
                        </a>";
                })
                ->addColumn('trans_rel',function($row){
                    return trans("common.$row->trans_code");
                })
                ->addColumn('relative_info',function($row){
                    $image = "<img alt='member_image' src='$row->relative_image' style='object-fit: cover;border-radius: 30px' width='50' height='50' />";
                    $nameContainer = "<div class='d-flex flex-column px-2 py-1'>
                                        <span class='font-weight-bold text-dark'>$row->name_relative</span>
                                        <span class='font-weight-normal' style='font-size: 0.90rem;margin-top: 2px'>$row->relative_member_type</span>
                                    </div>";
                    return "<div class='d-flex flex-row'>
                                $image
                                $nameContainer
                            </div>";
                })
                ->rawColumns(['actions','relative_info'])
                ->make(true);
        }
        return null;
    }

    public function removeRelation(Request $request){
        $relationId = $request['remove_relation_id'];
        $result = DB::table('member_relation')->where('id',$relationId)->delete();
        return response(['message' => 'relation Removed'],201);
    }

    public function promoteVisitorToMember(Request $request){
        $id = $request['visitor_id'];
        $visitor = Visitor::FindOrFail($id);
        $this->data['visitor'] = $visitor;
        return view('members.visitorToMember')->with('data',$this->data);
    }

    public function storePromotedVisitor(Request $request){

    }

    public function getParents(Request $request){
        $infantId = $request['infant_id'];
        $parents = $this->memberService->getParents($infantId);
        return response()->json($parents,200);
    }

    public function endMembership(Request $request){
        $data = $request->validate([
            'remove_date' => 'required|date',
            'member_id' => 'required',
            'remove_reason' => 'required'
        ]);
        $result = $this->memberService->endMembership($data);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label')],200);
        }
        return response()->json(['message'=> trans('common.general_error')],500);
    }

    public function reactivateMember(Request $request){
        $data = $request->validate([
            'member_id' => 'required'
        ]);
        $result = $this->memberService->reactivateMembership($data);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label')],200);
        }
        return response()->json(['message'=> trans('common.general_error')],500);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getMembershipHistory(Request $request){
        return DataTables::of($this->memberService->getMemberHistory($request['member_id']))
            ->addColumn('start_info',function ($row){
                if(app()->getLocale() == 'nl'){
                    setlocale(LC_TIME,'Dutch');
                }
                return ucWords(Carbon::parse($row->start_date)->formatLocalized('%A %d %B %Y %T'));
            })
            ->addColumn('end_info',function ($row){
                if(isset($row->end_date)){
                    if(app()->getLocale() == 'nl'){
                        setlocale(LC_TIME,'Dutch');
                    }
                    return ucWords(Carbon::parse($row->end_date)->formatLocalized('%A %d %B %Y %T'));
                }
                return $row->end_date;
            })
            ->make(true);
    }

    public function checkStatus(Request $request){
        $id = $request['member_id'];
        $member = MemberInfo::find($id);
        return response()->json(['member' => $member],200);
    }

    public function birthDaysExport(Request $request){
        $now = isset($request['start_date']) ? Carbon::parse($request['start_date']): now();
        $startDate = isset($request['start_date']) ? Carbon::parse($request['start_date'])->toDateString() :  $now->startOfWeek()->toDateString();
        $endDate = isset($request['end_date']) ? Carbon::parse($request['end_date'])->toDateString() : $now->endOfWeek()->toDateString();
        $weekNumber = $now->weekOfYear;
        $name = trans('common.birth_days') . " Week: $weekNumber";
        $fileName = "$name.xlsx";
        $data = [
            'start_date' => $startDate,
            'end_date' =>$endDate,
            'week' => $weekNumber
        ];
        return Excel::download(new BirthDayExport($name,$data),$fileName);
    }

    public function exportMembers(Request $request){
        $name = trans('common.member_overview_label') . '_' . now()->toDateString() . '.xlsx';
        return Excel::download(new MemberExport(trans('common.member_overview_label'),$request->all()),$name);
    }
}
