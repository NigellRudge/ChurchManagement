<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Role;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RoleController extends CommonController
{
    private $userService;
    public function __construct(UserService $service)
    {
        parent::__construct();
        $this->data['category_name'] = 'Config';
        $this->data['controller_name'] = 'Roles';
        $this->userService = $service;
    }

    public function index(Request $request){
        if($request->ajax()){
            return DataTables::of($this->userService->getRoles($request->all()))
                ->addColumn('actions', function($row){
                    return
                        "<a class='btn btn-xs btn-teal mr-1 rounded' onclick='editRole(event)' data-id='$row->id'>
                            <i class='fa fa-edit' data-id='$row->id'></i>
                        </a>"
                        ."<a class='btn btn-xs btn-danger rounded' data-id='$row->id' data-name='$row->name' onclick='deleteRole(event)'>
                            <i class='fa fa-trash' data-id='$row->id' data-name='$row->name'></i>
                        </a>";
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('roles.index')->with('data',$this->data);
    }

    public function store(Request $request){
        $data = $request->validate([
           'name' => 'required',
           'code' => 'required',
           'modules' => 'required|array'
        ]);

        $result = $this->userService->storeRole($data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],201);
        }
        return response()->json(['message' => trans('common.general_error')],401);

    }

    public function update(Request $request){

    }

    public function destroy(Request $request){
        $data = $request->validate([
            'role_id' => 'required'
        ]);
        $result = $this->userService->deleteRole($data['role_id']);
        if($result){
            return response()->json(['message' => trans('common.record_deleted_label')],201);
        }
        return response()->json(['message' => trans('common.general_error')],401);
    }

    public function moduleList(Request $request){
        $term = $request['name'];
        $page = $request['page'] ?? null;
        $resultCount = 10;
        $offset = ($page-1) * $resultCount;

        $results = Module::select(['id','name as text'])
            ->where('name', 'like', "%$term%")
            ->orderBY('id');

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
