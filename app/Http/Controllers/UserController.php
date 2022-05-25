<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Hash;


class UserController extends Controller
{

    public function user_login_index(){
        return view("user/login");
    }

    public function user_login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|max:12|min:8'
        ]);
        $user = User::where('email','=',$request->email)->first();
        if($user && Hash::check($request->password, $user->password)){
            $request->session()->put('loginID', $user->id);
            return redirect("/");
        }else{
            return back()->with('fail', 'Email or Password is incorrect');
        }
    }

    public function user_register_index(){
        return view("user/register");
    }

    public function user_register(Request $request){
        $request->validate([
            'name'=>'required',
            'email' => 'required|email',
            'phone' => 'required|max:11|min:11',
            'gendre' => 'required',
            'password' => 'required|max:12|min:8'
        ]);
        $user= new User();
        $user->name= $request->name;
        $user->email= $request->email;
        $user->phone= $request->phone;
        $user->gendre= $request->gendre;
        $user->password= Hash::make($request->password);
        $result = $user->save();
        if($result){
            return back()->with('success','User Registered');
        }
        else{
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function user_index(){
        $data = array();
        if(session()->has('loginID')){
            $data = User::where('id','=',session()->get("loginID"))->first();
        }
        return view("user/home");
    }


    public function user_logout(){
        if(session()->has('loginID')){
            session()->pull('loginID');
            return redirect("/");
        }
    }


}
