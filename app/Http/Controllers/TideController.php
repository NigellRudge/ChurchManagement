<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Member;
use App\Models\Tide;
use App\utils\CustomUtils;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TideController extends CommonController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['controller_name'] = "Tides";
        $this->data['category_name'] = "Transactions";

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
            $tides = DB::table('tide_info')
                ->select('id','member','amount','date','currency_code');
            if(isset($request['currency_id']) &&  intval($request['currency_id']) != 0){
                $tides->where('currency_id','=',intval($request['currency_id']));
            }
            if((isset($request['from_date']) &&  ($request['from_date']) > 0 ) && (isset($request['to_date']) &&  strlen($request['to_date']) > 0) ){
                $from = Carbon::parse($request['from_date'])->toDateString();
                $to = Carbon::parse($request['to_date'])->toDateString();
                $tides->whereBetween('date',[ $from,$to ]);
            }
            //dd($members);
            return DataTables::of($tides)
                ->addColumn('actions', function ($row){
                    return "<a class='bg-danger pl-2 pr-2 pt-1 pb-1 text-white rounded font-weight-bold mr-1 text-xs' onclick='openRemoveModal(event)'  data-id='$row->id' data-member='$row->member' data-amount='$row->amount' style='cursor:pointer'>remove</a>"
                        ."<a class='bg-teal pl-2 pr-2 pt-1 pb-1 text-white rounded font-weight-bold mr-1 text-xs' onclick='openEditModal(event)'  data-id='$row->id' style='cursor:pointer'>edit</a>";

                })
                ->addColumn('newAmount',function($row){
                    return "<span class='font-weight-bold mr-1'>$row->currency_code</span>" . '$' . number_format($row->amount,2);
                })
                ->rawColumns(['actions','newAmount'])
                ->make(true);
        }
        $this->data['action_name'] = 'Index';
        $this->data['currencies'] = Currency::all();
        return view('tides.index')->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->data['action_name'] = 'Create';
        $this->data['currencies'] = Currency::all();
        return view('tides.create')->with('data',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id' => 'required',
            'date' => 'required',
            'currency_id' => 'required',
            'amount' => 'required|min:0.01'
        ]);
        $tide = Tide::create($data);
        return redirect(route('tides.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param Tide $tide
     * @return Application|RedirectResponse|Redirector
     */
    public function show(Tide $tide)
    {
        return redirect(route('tides.edit',['tide' => $tide]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tide $tide
     * @return Application|Factory|View
     */
    public function edit(Tide $tide)
    {
        $tide['date'] = CustomUtils::parseDate($tide['date']);
        $this->data['tide'] = $tide;
        $this->data['action_name'] = 'Create';
        $this->data['currencies'] = Currency::all();
        $this->data['member'] = Member::find($tide['member_id']);
        return view('tides.edit')->with('data',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Tide $tide
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request, Tide $tide)
    {
        $data = $request->validate([
            'member_id' => 'required',
            'date' => 'required',
            'currency_id' => 'required',
            'amount' => 'required|min:0.01'
        ]);
        $tide->update($data);
        return redirect(route('tides.index'))->with('success','record updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tide $tide
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy(Tide $tide)
    {
        $tide->delete();
        return redirect(route('tides.index'))->with('warning','record removed');
    }

    public function delete(Tide $tide){
        $this->data['tide'] = $tide;
        return view('tides.delete')->with('data',$this->data);
    }

    public function destroyAjax(Request $request){
        $id = $request['remove_tide_id'];
        $tide = Tide::findOrFail($id);
        $tide->delete();
        return response(['message'=>'Tide removed successfully'],201);
    }

    public function storeAjax(Request $request){
        $data = $request->validate([
            'member_id' => 'required',
            'date' => 'required',
            'currency_id' => 'required',
            'amount' => 'required|min:0.01'
        ]);
        $tide = Tide::create($data);
        return response(['message' => 'Tide added successfully'],201);
    }

    public function updateAjax(Request $request){
        $tide = Tide::findOrFail($request['edit_tide_id']);
        $data = $request->validate([
            'edit_member_id' => 'required',
            'edit_date' => 'required',
            'edit_currency_id' => 'required',
            'edit_amount' => 'required|min:0.01'
        ]);
        $tide['member_id'] = $data['edit_member_id'];
        $tide['date'] = $data['edit_date'];
        $tide['currency_id'] = $data['edit_currency_id'];
        $tide['amount'] = $data['edit_amount'];
        $tide->save();
        return response(['message' => 'Tide updated successfully'],201);
    }

    public function getByIdAjax(Request $request){
        $tide = Tide::findOrFail($request['tideId']);
        return response()->json(['tide'=>$tide],201);

    }

}
