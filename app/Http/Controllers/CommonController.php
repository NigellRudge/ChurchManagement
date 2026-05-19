<?php


namespace App\Http\Controllers;


use App\Models\Currency;
use App\Models\District;
use App\Models\Gender;
use App\Models\MemberInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    protected $data = array();
    protected $user = null;
    public  function __construct()
    {
        $this->data['controller_name'] = '';
        $this->data['action_name'] = '';
        $this->data['category_name'] = '';
        $this->data['user'] = null;
        $this->data['currencies'] = Currency::all();
        $this->data['genders'] = Gender::all();
        $this->data['districts'] = District::all();


    }
    protected function getItemStatusColumn($status, $value){
        $spanStyle = "style='font-size: 0.8rem;padding:4px;border-radius: 10px;font-weight: 600'";
        switch ($status){
            case 0:
                $color='bg-danger';
                $temp = trans('common.inactive_label');
                return "<span class='bg-danger-light text-danger-dark font-weight-bold' $spanStyle>$temp</span>";
                break;
            default:
                $temp = trans('common.active_label');
                return "<span class='bg-success-light text-success-dark font-weight-bold' $spanStyle>$temp</span>";
                break;
        }

    }


    protected function getCreatedByColumn($value){
        return "<span class='d-flex flex-row'><i class='fa fa-user-tie text-teal mr-2'></i>$value</span>";
    }


    protected function getMemberImage($memberId,$raw=true){
        $memberInfo = MemberInfo::find($memberId);
        $image = $memberInfo->image;
        if($raw){
            return "<img alt='member_image' src='$image' style='object-fit: cover;border-radius: 12px' width='60' height='60' />";
        }
        return $image;
    }

}
