<?php

namespace App\Http\Controllers;

use App\Models\Module;
use \App\Models\User;
use App\Models\UserModuleAccessInfo;
use App\Services\UserService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends CommonController
{
    private $userService;
    public function __construct(UserService $service)
    {
        parent::__construct();
        $this->data['controller_name'] = 'Users';
        $this->data['category_name'] = 'Config';
        $this->userService = $service;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){
        if($request->ajax()){
            $users = DB::table('users')->select('*');
            return DataTables::of($users)
                ->addColumn('actions', function ($row){
                    if(auth()->user()->id == $row->id){
                        return "<a class='btn btn-info btn-sm rounded mr-1 text-white font-weight-bold' onclick='changePassword(event)' data-id='$row->id'  data-name='$row->name'>
                                <i class='fa fa-lock' data-id='$row->id'  data-name='$row->name'></i>
                            </a>";
                    }
                    if (auth()->user()->is_admin){
                        return "<a class='btn btn-info btn-sm rounded mr-1 text-white font-weight-bold' onclick='changePassword(event)' data-id='$row->id'  data-name='$row->name'>
                                <i class='fa fa-lock' data-id='$row->id'  data-name='$row->name'></i>
                            </a>"
                            ."<a class='btn btn-teal btn-sm mr-1 rounded text-white font-weight-bold' onclick='editUser(event)' data-id='$row->id'  data-name='$row->name'>
                                <i class='fa fa-edit' data-id='$row->id'  data-name='$row->name'></i>
                            </a>"
                            ."<a class='btn btn-danger btn-sm rounded text-white font-weight-bold' onclick='deleteUser(event)' data-id='$row->id'  data-name='$row->name'>
                                <i class='fa fa-trash' data-id='$row->id'  data-name='$row->name'></i>
                            </a>";
                    }
                    return "";
                })
                ->addColumn('user_info',function ($row){
                    $value = $row->is_admin ? trans('common.admin_user'): trans('common.normal_user');
                    $icon = $row->is_admin ? 'fa-user-tie text-teal' : 'fa-user';
                    return "<div class='d-flex flex-row'>
                                <i class='fa $icon mr-1'></i>
                                <span class='font-weight-normal text-secondary'>$value</span>
                            </div>";
                })
                ->rawColumns(['actions','user_info'])
                ->make(true);
        }
        $this->data['action_name'] = 'index';
        $this->data['modules'] = Module::all();
        return view('config.users.index')->with('data',$this->data);
    }


    public function view(User $user){

        $this->data['user'] = $user;
        return view('config.users.view')->with('data',$this->data);
    }

    public function store(Request $request){
        $data = $request->validate([
            'user_name' => 'required',
            'email' => 'required',
            'is_admin' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);
        if(intval($data['is_admin']) != 1){
            $data['modules'] = $request['modules'];
        }
        $result = $this->userService->storeUser($data);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label')],201);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }


    public function destroy(Request $request){
        $data = $request->validate([
            'user_id' => 'required'
        ]);
        $result = $this->userService->deleteUser($data['user_id']);
        if($result){
            return response()->json(['message'=> trans('common.record_deleted_label')],201);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }

    public function update(Request $request){
        $data = $request->validate([
            'user_id' => 'required',
            'user_name' => 'required',
            'email' => 'required',
            'is_admin' => 'required',
        ]);
        if(intval($data['is_admin']) != 1){
            $data['modules'] = $request['modules'];
        }
        $result = $this->userService->updateUser($data);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label')],201);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }

    public function changePassword(Request $request){
        $data = $request->validate([
            'user_id' => 'required',
            'new_password' => 'required',
            'old_password' => 'required',
            'confirm_password' => 'required',
        ]);

        $result = $this->userService->changePassword($data);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label')],201);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }

    public function getById(Request $request){
        $id = $request['user_id'];
        $user = User::find($id);
        $modules = UserModuleAccessInfo::where('user_id','=',$user->id)->select('*')->get();
        return response()->json(['user' => $user,'modules' =>$modules],201);
    }
}
