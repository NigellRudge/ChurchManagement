<?php

namespace App\Http\Controllers;

use App\Models\Country;
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

class CountryController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['controller_name'] = 'Country';
        $this->data['category_name'] = 'Config';
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){

        if($request->ajax()){
            $countries = Country::select([
                'id','name','code', DB::raw('CASE WHEN active= 1 THEN "Active" ELSE "In-active" END as status')
            ])->get();
            //dd($countries);
            return DataTables::of($countries)
                ->addColumn('actions', function ($row){
                    $editUrl = route('country.edit',['country' => $row->id]);
                    $deleteUrl = route('country.delete',['country' => $row->id]);
                    $showUrl = '';
                    return '<a class="bg-success py-1 px-1 text-white rounded font-weight-bold mr-1 text-xs" href="' . $editUrl . '">Edit</a>'
                        .'<a class="bg-danger py-1 px-1 text-white rounded font-weight-bold mr-1 text-xs" href="' . $deleteUrl .'">delete</a>';


                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('config.country.index')->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {

        return view('config.country.create')->with('data',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|min:5|max:50',
            'code'=>'required|max:5'
        ]);
        $country = new Country();
        $country->name = $request['name'];
        $country->code = $request['code'];
        if(isset($request['active'])){
            $country['active'] = 1;
        }
        else {
            $country['active'] = 0;
        }
        $country->save();
        return redirect(route('country.index'))->with('success','record added');
    }

    /**
     * Display the specified resource.
     *
     * @param Country $country
     * @return Application|RedirectResponse|Redirector
     */
    public function show(Country $country)
    {
        return redirect(route('country.edit',['country'=>$country]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Country $country
     * @return Application|Factory|View
     */
    public function edit(Country $country)
    {
        $this->data['country'] = $country;
        return view('config.country.edit')->with('data',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Country $country
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name'=>'required|min:5|max:50',
            'code'=>'required|max:5'
        ]);
        $country['name'] = $request['name'];
        $country['code'] = $request['code'];
        if(isset($request['active'])){
            $country['active'] = 1;
        }
        else {
            $country['active'] = 0;
        }
        $country->save();
        return redirect(route('country.index'))->with('data',$this->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Country $country
     * @return Application|RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(Country $country)
    {
        $country->delete();
        return redirect(route('country.index'))->with('success','record deleted');
    }

    public  function delete(Country $country){
        $this->data['country'] = $country;
        return view('config.country.delete')->with('data',$this->data);
    }

    public function getCountriesJson(Request $request){
        $term = $request['searchTerm'];
        $results = Country::select(['id',DB::raw('CONCAT(name, " ","(",code,")") as "text"')])
            ->where('name', 'like', "%$term%")
            ->orWhere('code', 'like',"%$term%")->get();

        return response()->json([
            'results'=>$results
        ]);
    }

}
