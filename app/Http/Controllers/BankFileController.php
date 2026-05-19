<?php

namespace App\Http\Controllers;

use App\Models\BankFileType;
use App\Services\BankFileService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BankFileController extends CommonController
{
    private $bankFileService;
    public function __construct(BankFileService $service)
    {
        parent::__construct();
        $this->data['controller_name'] = 'BankFiles';
        $this->data['category_name'] = config('constants.MODULE_CATEGORY_FINANCE');
        $this->bankFileService = $service;
    }

    public function index(Request $request){
        if($request->ajax()){
            return DataTables::of($this->bankFileService->getAllFiles($request->all()))
                ->addColumn('actions',function($row){
                    $showUrl = route('bankfiles.view_transactions',['bankFileId' => $row->id]);
                    return "<a class='btn btn-sm btn-primary rounded mr-1' href='$showUrl'>
                                <i class='fa fa-eye'></i>
                            </a>"
                            ."<a class='btn btn-sm btn-danger rounded mr-1' href='#' onclick='deleteFile(event)' data-id='$row->id'>
                                <i class='fa fa-edit' data-id='$row->id' onclick='editSeed(event)'></i>
                            </a>";

                })
                ->addColumn('status_info',function($row){
                    $spanStyle = "style='font-size: 0.8rem;padding:4px;border-radius: 10px;font-weight: 600'";
                    $style='';
                    $status='';
                    switch ($row->status){
                        case config('constants.BANK_FILE_STATUS_PENDING'):
                            $status = trans('common.pending');
                            $style='bg-secondary text-dark font-weight-bold';
                            break;
                        case config('constants.BANK_FILE_STATUS_MATCHING'):
                            $status = trans('common.matching');
                            $style = 'bg-warning text-dark font-weight-bold';
                            break;
                        case config('constants.BANK_FILE_STATUS_MATCHED'):
                            $status = trans('common.matched');
                            $style = 'bg-success text-success-dark font-weight-bold';
                            break;
                        default;
                    }
                    return "<span class='$style' style='$spanStyle'>$status</span>";
                })
                ->rawColumns(['actions','status_info'])
                ->make(true);
        }
        $this->data['bank_file_types'] = BankFileType::all();
        return view('bankfiles.index')->with('data',$this->data);
    }

    public function store(Request $request){
        $data = $request->validate([
            'file_name' => 'required',
            'file' => 'required|file',
            'bank_file_type' => 'required'
        ]);
        $result = $this->bankFileService->addBankFile($data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],201);
        }
        return response()->json(['message' => trans('common.general_error')],501);
    }


    public function destroy(Request $request){

    }

    public function view(Request $request){

    }

    public function storeBankFileTransaction(Request $request, $bankFileId){

    }

    public function editBankFileTransaction(Request $request, $bankFileId){

    }

    public function destroyBankFileTransaction(Request $request, $bankFileId){

    }

}
