<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Hash;
use MongoDB\Driver\Session;


class EmployeeController extends Controller
{
    //
    public function employee_login_index(){
        return view("employee/login");
    }

    public function employee_login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|max:12|min:8'
        ]);
        $employee = employee::where('email','=',$request->email)->first();
        if($employee && Hash::check($request->password, $employee->password)){
            $request->session()->put('loginID', $employee->id);
            return redirect("/");
        }else{
            return back()->with('fail', 'Email or Password is incorrect');
        }
    }

    public function employee_index(){
        $data = array();
        if(session()->has('loginID')){
            $data = User::where('id','=',session()->get("loginID"))->first();
        }
        return view("employee/home");
    }

    public function employee_logout(){
        if(session()->has('loginID')){
            session()->pull('loginID');
            return redirect("/");
        }
    }
}