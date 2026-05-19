<?php


namespace App\Services;


use App\Models\UserModuleAccessInfo;
use Illuminate\Support\Facades\Auth;

class PermissionService
{
    private $user;
    private $moduleAccessData;
    public function __construct()
    {
        $this->user = Auth::user();
        $this->getModuleAccessData();
    }

    public function getModuleAccessData(){
        $this->moduleAccessData = collect(UserModuleAccessInfo::where('user_id','=', $this->user->id)->select('*')->get());
    }

    public function checkModulePermission($moduleId){
        if($this->user->is_admin){
            return true;
        }
        return $this->moduleAccessData->contains(function($item)use($moduleId){
            return $item->module_id == $moduleId;
        });
    }
    public function checkCategoryPermission($category){
        if($this->user->is_admin){
            return true;
        }
        return $this->moduleAccessData->contains(function($item)use($category){
            return $item->module_category == $category;
        });
    }
}
