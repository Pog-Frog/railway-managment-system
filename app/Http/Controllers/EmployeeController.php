<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Hash;


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
        $employee = Employee::where('email','=',$request->email)->first();
        if($employee && Hash::check($request->password, $employee->password)){
            $request->session()->put('employeeloginID', $employee->id);
            return redirect("employee/home");
        }else{
            return back()->with('fail', 'Email or Password is incorrect');
        }
    }

    public function employee_index(){
        $data = array();
        if(session()->has('employeeloginID')){
            $data = Employee::where('id','=',session()->get("employeeloginID"))->first();
        }
        return view("employee/home", compact('data'));
    }

    public function employee_logout(){
        if(session()->has('employeeloginID')){
            session()->pull('employeeloginID');
            return redirect("employee/");
        }
    }
}
