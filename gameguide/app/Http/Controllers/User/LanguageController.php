<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
class LanguageController extends Controller
{
    public function languageSwitch(Request $request){
        $lang = $request->language ? $request->language : 'en';
        //session('language',$request->language);
        session()->put('language', $lang);
        //var_dump();
        //var_dump(session('language'),$request->language);exit;
        //return true;
        return redirect()->back()->with(['status'=>true]);
    
    }
}