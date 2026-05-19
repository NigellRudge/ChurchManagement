<?php


namespace App\Services;


use App\Models\EagleGroup;
use App\Models\EagleMemberInfo;
use Illuminate\Support\Facades\DB;

class EagleService
{
    public function getGroupMembers($groupId){
        return  EagleMemberInfo::where('group_id',$groupId)
            ->select('id',
                'name',
                'gender',
                'gender_id',
                'phone_number',
                'email','image','member_type'
            );
    }

    public function getNotYetMembers($groupId,$captainId,$page=1,$term){
        $active_member_ids = DB::table('eagle_memberships')
            ->select('member_id')->get()->toArray();
        $ids = array();

        foreach ($active_member_ids as $active_member_id){
            array_push($ids,$active_member_id->member_id);
        }
        //array_push($ids,$captainId);

        $resultCount = 10;
        $offset = ($page-1) * $resultCount;
        $results = DB::table('member_info')
            ->select(['id',DB::raw('name as text')])
            ->whereIn('member_type_id',[1,5,6])
            ->whereNotIn('id',$ids)
            ->where('name', 'like', "%$term%");
        if($page != null){
            $results->skip($offset)->take($resultCount);
        }
        return $results;
    }

    public function saveEagleGroup(array $data){
        return EagleGroup::create($data);
    }

    public function deleteGroup($groupId){
        DB::transaction(function() use($groupId){
            DB::table('eagle_memberships')
                ->where('group_id','=',$groupId)
                ->delete();

            DB::table('eagle_groups')
                ->where('id','=',$groupId)
                ->delete();
        });
        return 1;
    }

    public function updateEagleGroup($groupId,array $data){
        return DB::table('eagle_groups')
            ->where('id',$groupId)
            ->update([
                'name' => $data['edit_name'],
                'team_captain' => $data['edit_team_captain']
            ]);
    }

    public function removeMember($groupId,$memberId):int{
        return DB::table('eagle_memberships')
            ->where('group_id','=',$groupId)
            ->Where('member_id','=',$memberId)
            ->delete();
    }

    public function addMember($groupId,$memberId): int{
        return DB::table('eagle_memberships')
            ->insert([
                'group_id' => $groupId,
                'member_id' => $memberId
            ]);
    }

    public function getEagleGroups(array $data = null){
        return DB::table('eagle_group_info')
            ->select([
                    'id',
                    'team_captain',
                    'name',
                    'num_members'
                ]
            );
    }
}
