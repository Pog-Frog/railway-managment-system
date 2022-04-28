<?php

namespace App\Http\Controllers;
use App\Train;
use App\Train_type;
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
            return back()->with('fail', 'Something went wrong');
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
        if(session()->has('loginID')){
            session()->pull('loginID');
            return redirect('admin/');
        }
    }

    public function employees_index(){
        $data = Admin::where('id','=',session()->get("loginID"))->first();
        return view("admin/employees_management_index", compact('data'));
    }

    public function trains_index(){
        $data = Admin::where('id','=',session()->get("loginID"))->first();
        return view("admin/trains_management_index", compact('data'));
    }

    public function insert_train(Request $request){
        $request->validate([
            'number' => 'required',
            'type' => 'required',
            'no_of_cars' => 'required'
        ]);
        $train= new Train();
        $train->number= $request->number;
        $train->no_of_cars= $request->no_of_cars;
        $train->type= $request->type;
        $train->status= $request->status;
        $result = $train->save();
        if($result){
            return back()->with('success','Train Saved');
        }
        else{
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function insert_train_type(Request $request){
        $request->validate([
            'name' => 'required',
        ]);
        $train_type= new Train_type();
        $train_type->name= $request->name;
        $result = $train_type->save();
        if($result){
            return back()->with('success','Train Saved');
        }
        else{
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function insert_train_type_index(){
        return view('admin/insert_train_type');
    }
}
