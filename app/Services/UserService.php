<?php


namespace App\Services;


use App\Models\Module;
use App\Models\User;
use App\Models\UserModuleAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function getRoles(array $filterOptions = null){
        $items = Role::all();
        return $items;
    }

    public function storeRole(array $data){
        try {
            DB::transaction(function() use($data){
                $role = Role::create([
                    'name' => $data['name'],
                    'code' => $data['code'],
                ]);
                foreach ($data['modules'] as $module){
                    RoleModuleAccess::create([
                        'role_id' =>$role->id,
                        'module_id' => $module
                    ]);
                }
            });
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function deleteRole($roleId){
        try {
            $role = Role::find($roleId);
            $role->delete();
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function storeUser(array $inputData){
        //try {
            $data['name'] = $inputData['user_name'];
            $data['email'] = $inputData['email'];
            $data['language_id'] = 1;
            $data['is_admin'] = $inputData['is_admin'] == 1;
            $data['password'] = Hash::make($inputData['password']);
            DB::transaction(function() use($data,$inputData){
                $user = User::create($data);
                if(isset($inputData['modules'])){
                    foreach ($inputData['modules'] as $module){
                        UserModuleAccess::create([
                            'user_id' => $user->id,
                            'module_id' => $module
                        ]);
                    }
                }
            });
            return true;
//        }
//        catch (\Exception $exception){
//            return false;
//        }

    }

    public function updateUser(array $inputData){
       // try {
            $data['name'] = $inputData['user_name'];
            $data['email'] = $inputData['email'];
            $data['language_id'] = 1;
            DB::transaction(function() use($data,$inputData){
                $user = User::find($inputData['user_id']);
                $user->update($data);
                $user->save();
                UserModuleAccess::where('user_id','=',$user->id)->delete();
                if(isset($inputData['modules'])){
                    foreach ($inputData['modules'] as $module){
                        UserModuleAccess::create([
                            'user_id' => $user->id,
                            'module_id' => $module
                        ]);
                    }
                }
            });
            return true;
//        }
//        catch (\Exception $exception){
//            return false;
//        }
    }

    public function changePassword(array $data){
        try {
            $user = User::find($data['user_id']);
            $currentPassword = $user->password;
            if(!Hash::check($data['old_password'],$currentPassword)){
                return false;
            }
            $user->password = Hash::make($data['new_password']);
            $user->save();
            Auth::login($user);
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function deleteUser($userId){
        try {
            $user = User::find($userId);
            $user->delete();
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }
}
