<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LocationsController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function countryIndex(Request $request){
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
        return view('config.location.country.index')->with('data',$this->data);
    }

    public  function createCountry(){
        return view('config.Location.country.create')->with('data',$this->data);
    }

    public  function editCountry(Country $country){
        $this->data['country'] =  $country;
        return view('config.Location.country.create')->with('data',$this->data);
    }

    public function storeCountry(Country $country){


    }

    public function updateCountry(Request $request, Country $country){

    }

    /**
     * @param Country $country
     * @return Application|Factory|View
     */
    public function deleteCountry(Country $country){

        $this->data['country'] = $country;
        return view('config.Location.country.delete')->with('data',$this->data);
    }

    /**
     * @param Country $country
     * @return Application|RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroyCountry(Country $country){
        $country->delete();
        return redirect(route('country.index'))->with('success','record deleted');
    }
}
