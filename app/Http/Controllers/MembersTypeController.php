<?php

namespace App\Http\Controllers;

use App\Models\MemberType;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MembersTypeController extends CommonController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['controller_name']= 'member types';
        $this->data['category_name'] = 'Config';
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

            $types = MemberType::select('id','name','active','code',DB::raw('CASE WHEN active = 1 THEN "Active" ELSE "In-active" END  as status'));
            return DataTables::of($types)
                ->addColumn('actions', function ($row){
                    return "<a class='btn-teal btn btn-sm rounded text-white  font-weight-bold mr-1 ' onclick='openEditModal(event)' data-id='$row->id' data-name='$row->name'>
                                <i class='fa fa-edit' data-id='$row->id' data-name='$row->name'></i>
                             </a>"
                        ."<a class='btn btn-danger btn-sm rounded text-white font-weight-bold'  onclick='openRemoveModal(event)'  data-id='$row->id' data-name='$row->name'>
                            <i class='fa fa-trash' data-id='$row->id' data-name='$row->name'></i>
                          </a>";

                })
                ->rawColumns(['actions','status_info'])
                ->make(true);
        }
        $this->data['action_name'] = 'index';
        if(auth()->check()){
            $this->data['user'] = auth()->user();
        }
        return view('config.MemberTypes.index')->with('data',$this->data);
    }

    public function addAjax(Request $request){
        $data = $request->validate([
            'name' => 'required|max:50',
            'code' => 'required|max:6'
        ]);
        //dd($data);
        if(isset($data['active'])){
            $data['active'] == 'on'? $data['active'] = 1 : $data['active'] = 0;
        }
        $type = MemberType::create($data);
        return response(['message'=>'Type created'],201);
    }

    public function destroyAjax(Request $request){
        $id = $request['remove_type_id'];
        DB::table('member_types')
            ->where('id','=',$id)
            ->delete();

        return response(['message'=> 'types removed'],201);
    }
    public function getByIdAjax(Request $request){
        $id = $request['typeId'];
        $type = DB::table('member_types')
                    ->where('id','=',$id)
                    ->select('id','name','code','active')->get();
        return response()->json(['type'=>$type],201);
    }

    public function updateAjax(Request $request){
        $id = $request['edit_type_id'];
        $data = $request->validate([
            'edit_name' => 'required|max:50',
            'edit_code' => 'required|max:6'
        ]);
        (($request['edit_active'] != null) && ($request['edit_active'] == 'on'))? $data['edit_active'] = true : $data['edit_active'] = false;
        $type = MemberType::findOrFail($id);
        $type['name'] = $data['edit_name'];
        $type['code'] = $data['edit_code'];
        $type['active'] = $data['edit_active'];
        $type->save();

        return response(['message'=>'Member Type updated'],201);
    }
}
