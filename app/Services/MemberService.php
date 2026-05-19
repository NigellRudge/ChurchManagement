<?php


namespace App\Services;


use App\Models\InfantDedicationInfo;
use App\Models\Member;
use App\Models\MemberFile;
use App\Models\MemberFileInfo;
use App\Models\MemberInfo;
use App\Models\MemberMembership;
use App\Models\MemberRelationInfo;
use App\Models\ServiceClubMemberInfo;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use phpDocumentor\Reflection\Types\Boolean;



class MemberService
{

    public function getAll(array $filterOptions){
        $members = MemberInfo::whereNull('deleted_at')->select(['*'])->orderby('name','asc');
        if(isset($filterOptions['gender_id']) &&  intval($filterOptions['gender_id']) != 0){
            $members->where('gender_id','=',intval($filterOptions['gender_id']));
        }
        if(isset($filterOptions['member_type_id']) &&  intval($filterOptions['member_type_id']) != 0){
            $members->where('member_type_id','=',intval($filterOptions['member_type_id']));
        }
        if(isset($filterOptions['dedicated']) &&  intval($filterOptions['dedicated']) != true){
            $members->where('dedicated','!=', null);
        }
        if(isset($filterOptions['statusId']) &&  $filterOptions['statusId'] != 3){
            $members->where('active','=',$filterOptions['statusId']);
        }
        if(isset($filterOptions['baptized']) &&  $filterOptions['baptized'] != 0){
            $status = $filterOptions['baptized'] == 1;
            $members->where('baptized','=',$status);
        }
        if(isset($filterOptions['from_age']) &&  $filterOptions['from_age'] != 0){
            $members->where('age','>=',$filterOptions['from_age']);
        }
        if(isset($filterOptions['to_age']) &&  $filterOptions['to_age'] != 0){
            $members->where('age','<=',$filterOptions['to_age']);
        }
        return $members;
    }

    public function getRelationships($memberId,array $filterOptions = null){
        return MemberRelationInfo::where('member_id',$memberId)
            ->select('id','relative_id','name_relative','relation','trans_code','relative_member_type','relative_image','relative_age')->get();
    }

    public function getConverts(array $data){
        $converts = DB::table('converts_info')
            ->select([
                'id',
                'name',
                'gender',
                'address',
                'gender_id',
                'trans_string',
                'phone_number',
                'convert_date'
            ]);
        if(isset($data['gender_id']) &&  intval($data['gender_id']) != 0){
            $converts->where('gender_id','=',intval($data['gender_id']));
        }
        if((isset($data['from_date']) &&  ($data['from_date']) > 0 ) && (isset($data['to_date']) &&  strlen($data['to_date']) > 0) ){
            $from = Carbon::parse($data['from_date'])->toDateString();
            $to = Carbon::parse($data['to_date'])->toDateString();
            $converts->whereBetween('convert_date',[ $from,$to ]);
        }
        return $converts;
    }

    public function createMember(Request $request, array $data){
        try {
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
            $data['id_number'] = isset($request['id_number']) ? $request['id_number'] : null;
            $member = Member::create($data);
            if ($request->hasFile('image')){
                $file_name = $member->first_name . '-' . $member->last_name . \Illuminate\Support\Carbon::now()->toDateString() . '.' . $request->file('image')->getClientOriginalExtension();
                $destination = 'public/uploads/images/users/';
                $request->file('image')->storeAs($destination,$file_name);
                $member->image = $file_name;
                $member->save();
            }
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function removeMember(array $data){

    }

    public function getDedicatedInfants(array $filterOptions){
        $items = InfantDedicationInfo::select(['*']);
        if(isset($filterOptions['gender_id']) && $filterOptions['gender_id'] != 0){
            $items->where('gender_id','=',$filterOptions['gender_id']);
        }
        if(isset($filterOptions['start_date'])){
            $items->whereDate('dedication_date','>=',Carbon::parse($filterOptions['start_date']));
        }
        if(isset($filterOptions['end_date'])){
            $items->whereDate('dedication_date','<=',Carbon::parse($filterOptions['end_date']));
        }
        return $items;
    }

    public function removeDedicatedInfant($infantId){
        try{
            DB::table('infant_dedications')->where('id','=',$infantId)->delete();
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function dedicateInfant(array $data){
        $data['created_at'] = Carbon::now()->toDateTimeString();
        $data['updated_at'] = Carbon::now()->toDateTimeString();
        $data['dedication_date'] = Carbon::parse($data['dedication_date'])->toDateTimeString();
        try {
            DB::table('infant_dedications')->insert($data);
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function getParents($infantId){
        $motherId = DB::table('member_relation')->where('member_id','=',$infantId)
                        ->where('relationship_type_id','=',3)->select('related_member_id')->first();
        $fatherId = DB::table('member_relation')->where('member_id','=',$infantId)
                        ->where('relationship_type_id','=',4)->select('related_member_id')->first();

        $mother = DB::table('member_info')->where('id','=',$motherId->related_member_id)->select('id','name')->first();
        $father = DB::table('member_info')->where('id','=',$fatherId->related_member_id)->select('id','name')->first();
        return ['mother' => $mother, 'father' => $father];
    }

    public function getDedicatedInfantById($id){
        try {
            $item = InfantDedicationInfo::find($id);
            return $item;
        }
        catch (\Exception $e){
            return null;
        }
    }

    public function updateDedicatedInfant(array $data){
        try {
            DB::table('infant_dedications')->where('id','=',$data['id'])->update([
                'dedication_date' =>Carbon::parse($data['dedication_date'])->toDateTimeString()
            ]);
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function endMembership(array $data){
        try{
            DB::table('members')->where('id','=',$data['member_id'])
                ->update([
                    'end_date' => Carbon::parse($data['remove_date'])->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                    'active' => 0
                ]);
            DB::table('member_memberships')->where('member_id','=',$data['member_id'])
                ->whereNull('end_date')
                ->update([
                    'end_date' => Carbon::parse($data['remove_date'])->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                    'end_reason' => $data['remove_reason']
                ]);

            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function reactivateMembership(array $data){
        try{
            $member = Member::find($data['member_id']);
            $member->end_date = null;
            $member->updated_at = now()->toDateTimeString();
            $member->active = 1;
            $member->save();

            DB::table('member_memberships')->insert([
                'member_id' => $data['member_id'],
                'membership_type_id' => $member->member_type_id,
                'start_date' => now()->toDateTimeString(),
                'end_date' => null,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => null,
                'end_reason' => null
            ]);
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function getServiceClubMembers(array $filterOptions){
        $items = ServiceClubMemberInfo::select('*');
        if(isset($filterOptions['gender_id']) && $filterOptions['gender_id'] != 0){
            $items->where('gender_id','=',$filterOptions['gender_id']);
        }
        return $items;
    }

    public function storeServiceClubMember(array $data){
        try {
            DB::table('service_club_members')->insert([
                'member_id' => $data['member_id'],
                'skills' => $data['skills'],
                'profession' => $data['profession'],
                'business_owner' => $data['business_owner'],
                'business_name' => $data['business_name'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
            $id = DB::table('service_club_members')->where('member_id','=',$data['member_id'])->select('id')->first()->id;
            if(isset($data['sectors'])){
                foreach($data['sectors'] as $sector){
                    DB::table('service_member_sectors')->insert([
                        'service_member_id' => $id,
                        'sector_id' => $sector
                    ]);
                }
            }
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function updateServiceClubMember(array $data){
//        try {
            DB::table('service_club_members')->where('member_id','=',$data['member_id'])->update([
                'skills' => $data['skills'],
                'profession' => $data['profession'],
                'business_owner' => $data['business_owner'],
                'business_name' => $data['business_name'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
            $id = DB::table('service_club_members')->where('member_id','=',$data['member_id'])->select('id')->first()->id;
            DB::table('service_member_sectors')->where('service_member_id','=',$id)->delete();
            if(isset($data['sectors'])){
                foreach($data['sectors'] as $sector){
                    DB::table('service_member_sectors')->insert([
                        'service_member_id' => $id,
                        'sector_id' => $sector
                    ]);
                }
            }
            return true;
//        }
//        catch (\Exception $exception){
//            return false;
//        }
    }

    public function removeServiceClubMember($id){
        try {
            DB::table('service_club_members')->where('id','=',$id)->delete();
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function getServiceMemberById($id){
        $member = ServiceClubMemberInfo::where('id','=',$id)->select('*')->first();
        $sectors = DB::table('service_member_sectors_info')->where('service_id','=',$id)->select('sector')->get();
        return [
            'member' => $member,
            'sectors' => $sectors
        ];
    }

    public function getMemberHistory($id){
        $items = DB::table('member_membership_history')->where('member_id','=',$id)->select('start_date','end_date','membership_type','end_reason');
        return $items;
    }

    public function getMemberFiles($memberId){
        return MemberFileInfo::where('member_id','=',$memberId)->select('*');
    }

    public function uploadFile(array $data){


    }
}
