<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Application;

class Language
{
    private $app;
    private $request;
    public function __construct(Application $service, Request $request)
    {
        $this->app = $service;
        $this->request = $request;
    }
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()){
            $userLang = auth()->user()->language_id;
            $otherLangs = DB::table('languages')->where('id','<>',$userLang)->select('*')->get();
            $lang = DB::table('languages')->where('id',$userLang)->first();
            $this->app->setLocale($lang->code);
            session()->put('lang_id',$lang->id);
            session()->put('lang_code',$lang->code);
            session()->put('lang_name',$lang->name);
            session()->put('lang_icon', asset('storage/' . $lang->icon_name));
            session()->put('other_langs', $otherLangs);
            //dd($otherLangs);
        }
        return $next($request);
    }
}
