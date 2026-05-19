<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberFile;
use App\Services\MemberService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MemberFileController extends CommonController
{
    private $memberService;
    public function __construct(MemberService $service)
    {
        parent::__construct();
        $this->memberService = $service;
    }


    public function memberFiles(Request $request){
        return DataTables::of($this->memberService->getMemberFiles($request['member_id']))
            ->addColumn('actions',function($row){
                $deleteTitle = trans('common.label_terminate_membership');
                $downloadRoute = route('memberFiles.downloadFile',['file_id' => $row->id]);
                $downloadTitle = trans('common.label_terminate_membership');
                return "<a class='btn btn-sm rounded-lg btn-danger mr-1' data-id='$row->id' data-file_name='$row->file_name' title='' onclick='deleteFile(event)'>
                            <i class='fas fa-trash' data-id='$row->id' data-file_name='$row->file_name'></i>
                        </a>".
                        "<a href='$downloadRoute'  class='btn btn-sm rounded-lg btn-primary ' data-id='$row->id' data-file_name='$row->file_name' title='$downloadTitle'>
                             <i class='fas fa-download download_member_file' data-id='$row->id' data-file_name='$row->file_name'></i>
                        </a>";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function uploadMemberFile(Request $request){
        $data = $request->validate([
            'file' => 'required|file|mimes:doc,docx,pdf,txt,jpg,jpeg,png,xlsx|max:5000',
            'member_id' => 'required',
            'name' => 'required'
        ]);
        $member = Member::find($data['member_id']);
        if($request->hasFile('file')){
            $file = $request->file('file');
            if(!file_exists('upload/member_files/')){
                mkdir('upload/member_files/', 0777, true);
            }
            $uploaded_by = Auth::user()->id;
            $name = $request['name'];
            $file_name = $file->getClientOriginalName();
            $file_name = str_replace(' ', '_', $file_name);
            $file_name = preg_replace('/[^A-Za-z0-9.\-]/', '', $file_name);
            $file_name = $member->id . '_' . $member->last_name . '_' . $member->fist_name . $file_name;
            $destination = 'upload/member_files/';
            $file->move($destination,$file_name);
            $memberFile = new MemberFile();
            $memberFile->name = $name;
            $memberFile->file_name = $file_name;
            $memberFile->uploaded_by = $uploaded_by;
            $memberFile->member_id = $member->id;
            $memberFile->save();
            if($memberFile){
                return response()->json(['message' => 'file_uploaded'],200);
            }
        }
        return response()->json(['message' => 'file_uploaded'],500);
    }

    public function delete(Request $request){
        $id = $request['file_id'];
        $file = MemberFile::find($id);
        $result = unlink('upload/member_files/' . $file->file_name);
        if($result){
            $file->delete();
            return response()->json(['message' => 'File removed'],200);
        }
        return response()->json(['message' => 'something went wrong'],500);
    }

    public function download(Request $request){
        $id = $request['file_id'];
        $file = MemberFile::find($id);
        return response()->download("upload/member_files/$file->file_name");
    }
}
