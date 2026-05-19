<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DistrictController extends CommonController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['controller_name'] = 'Districts';
        $this->data['category_name'] = 'Config';
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
            $districts = DB::table('districts')
                ->select(['id','name','code','active', DB::raw('CASE WHEN active= 1 THEN "Active" ELSE "In-active" END as status')]);
            //dd($countries);
            return DataTables::of($districts)
                ->addColumn('actions', function ($row){

                    return "<a class='btn-teal btn btn-sm rounded text-white  font-weight-bold mr-1' onclick='openEditModal(event)' data-id='$row->id' data-name='$row->name'>
                           <i class='fa fa-edit' data-id='$row->id' data-name='$row->name'></i>
                         </a>".
                        "<a class='btn btn-danger btn-sm rounded text-white font-weight-bold' onclick='openRemoveModal(event)' data-id='$row->id' data-name='$row->name'>
                                <i class='fa fa-trash' data-id='$row->id' data-name='$row->name'></i>
                           </a>"
                        ;

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('config.district.index')->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->data['countries'] = Country::all()->take(5);
       // dd($this->data);
        return view('config.district.create')->with('data',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
       //dd($request);
        $data = $request->validate([
            'name'=> 'required|min:4|max:50',
            'code'=> 'required|max:5',
        ]);

        //dd($data);
        $district =  District::create([
            'name'=> $data['name'],
            'code'=> $data['code'],
        ]);
        if(isset($data['active'])){
            if($data['active'] == 'on'){
                $district['active'] = 1;
            }
        }
        else{
            $district['active'] = 0;
        }

        $district->save();
        return redirect(route('district.index'))->with('success','record added');

    }

    /**
     * Display the specified resource.
     *
     * @param City $city
     * @return Application|RedirectResponse|Redirector
     */
    public function show(District $district)
    {
        return redirect(route('district.edit',['district'=>$city]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param District $district
     * @return Application|Factory|View
     */
    public function edit(District $district)
    {
        $this->data['district'] = $district;
        //dd($district->country);
        return view('config.district.edit')->with('data',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param District $district
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request, District $district)
    {
        $request->validate([
            'name'=> 'required|min:4|max:50',
            'code'=> 'required|max:5',
        ]);

        $district['name'] = $request['name'];
        $district['code'] = $request['code'];
        $district->save();
        return redirect(route('district.index'))->with('success','record updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param District $district
     * @return Application|RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(District $district)
    {
        $district->delete();
        return redirect(route('district.index'))->with('info','records deleted');
    }

    public function delete(District $district){
        $this->data['district'] = $district;
        return view('config.district.delete')->with('data',$this->data);
    }

    public function getDistrictsJson(Request $request){
        $term = $request['term'];
        $districts = DB::table('districts')
                        ->where('name','like',"%$term%")
                        ->select('id',DB::raw('name as text'))->get();
        return response()->json([
            'results' => $districts
        ]);
    }

    public function storeAjax(Request $request){
        $data = $request->validate([
            'name' => 'required|min:3|max:50',
            'code' => 'required|max:8'
        ]);
        $district = District::create($data);
        return response(['message'=>trans('common.record_stored_label')],201);

    }

    public function destroyAjax(Request $request){
        $district_id = $request['remove_district_id'];
        DB::table('districts')
            ->where('id','=',$district_id)
            ->delete();
        return response(['message'=> trans('common.record_deleted_label')],201);
    }

    public function getByIdAjax(Request $request){
        $id = $request['districtId'];
        $district = DB::table('districts')
                        ->where('id','=',$id)
                        ->select('id','name','code','active')->first();
        return response()->json(['district'=>$district],201);
    }

    public function updateAjax(Request $request){
        $id = $request['edit_district_id'];
        $data = $request->validate([
            'edit_name' => 'required|min:3|max:50',
            'edit_code' => 'required|max:8'
        ]);
        if(isset($data['edit_active'])){
            if($data['edit_active'] == 'on'){
                $data['edit_active'] = 1;
            }
        }
        else{
            $data['edit_active'] = 0;
        }
        $district = District::findOrFail($id);
        $district['name'] = $data['edit_name'];
        $district['code'] = $data['edit_code'];
        $district['active'] = $data['edit_active'];
        $district->save();
        return response(['message'=>trans('common.record_stored_label')],201);
    }


}
