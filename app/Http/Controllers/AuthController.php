<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        if(auth()->user()){
            return redirect(route('dashboard'));
        }
        return view('auth.login');
    }

    public function authenticate(Request $request,$backUrl=null){
        $credentials = $request->validate([
            'email' =>['required','email'],
            'password' => ['required']
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect(route('dashboard'));

        }
        return back()->withErrors([
            'errors' => trans('common.login_fail_error')
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function changeLanguage(Request $request){
        $langId = $request['language_id'];
        $user = Auth::user();
        $user->language_id = $langId;
        $user->save();
        return redirect()->back();
    }
}
