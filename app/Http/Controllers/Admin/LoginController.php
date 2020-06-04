<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest ;

class LoginController extends Controller
{
    public function getLogin(){
        return view('admin.auth.login');
    }
    public function Login(LoginRequest $request){
       $remeberMe = $request->has('remember_me') ? true : false ;

       if (auth()->guard('admin')->attempt(['email'=> $request->email , 'password' => $request->password])){
           return redirect()->route('admin.dashboard');
       }else{
           return redirect()->back()->with(['error'=>'هناك خطأ في البيانات']);
       }
    }
}
