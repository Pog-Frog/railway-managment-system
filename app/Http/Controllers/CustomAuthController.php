<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Admin;
use Hash;
use MongoDB\Driver\Session;

class CustomAuthController extends Controller
{
    public function admin_login_index(){
        return view("admin/login");
    }

    public function admin_login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|max:12|min:8'
        ]);
        $user = Admin::where('email','=',$request->email)->first();
        if($user && Hash::check($request->password, $user->password)){
            $request->session()->put('loginID', $user->id);
            return redirect('admin/dashboard');
        }else{
            return back()->with('fail', 'Email or Password is incorrect');
        }
    }
    public function admin_register_index(){
        return view("admin/register");
    }

    public function admin_register(Request $request){
        $request->validate([
            'name'=>'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|max:12|min:8'
        ]);
        $user= new Admin();
        $user->name= $request->name;
        $user->email= $request->email;
        $user->password= Hash::make($request->password);
        $result = $user->save();
        if($result){
            return back()->with('success','Admin Registered');
        }
        else{
            return back()->with('fail', 'sim ting wond');
        }
    }

    public function admin_index(){
        $data = array();
        if(session()->has('loginID')){
            $data = Admin::where('id','=',session()->get("loginID"))->first();
        }
        return view('admin/dashboard', compact('data'));
    }

    public function admin_logout(){

    }
}
