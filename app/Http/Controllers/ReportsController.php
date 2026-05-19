<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){
        return view('reports.index')->with('data',$this->data);
    }

    public function currency(Request $request){
        if($request->ajax()){

        }
        return view('reports.currency')->with('data',$this->data);
    }


}
