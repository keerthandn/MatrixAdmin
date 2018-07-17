<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login(Request $request)
    {
    	if($request->isMethod('post')){
    		$data=$request->input();
    		if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password'],'admin'=>'1'])){
    			// echo "Success"; die;
    			return redirect('/admin/dashboard');
    		}
    		else
    		{
    			// echo "Failed"; die;
    			return redirect('/admin')->with('flash_message_error','Invalid Username or Password ');
    		}
    	}
    	return view('admin.admin_login');
    }

    public function dashboard()
    {
    	return view('admin.dashboard');
    }
}
