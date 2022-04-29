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
    public function admin_login_index()
    {
        return view("admin/login");
    }

    public function admin_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|max:12|min:8'
        ]);
        $user = Admin::where('email', '=', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $request->session()->put('adminID', $user->id);
            return redirect('admin/dashboard');
        } else {
            return back()->with('fail', 'Email or Password is incorrect');
        }
    }

    public function admin_register_index()
    {
        return view("admin/register");
    }

    public function admin_register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|max:12|min:8'
        ]);
        $user = new Admin();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $result = $user->save();
        if ($result) {
            return back()->with('success', 'Admin Registered');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function admin_index()
    {
        $data = array();
        if (session()->has('adminID')) {
            $data = Admin::where('id', '=', session()->get("adminID"))->first();
        }
        return view('admin/dashboard', compact('data'));
    }

    public function admin_logout()
    {
        if (session()->has('adminID')) {
            session()->pull('adminID');
            return redirect('admin/');
        }
    }

    public function employees_index()
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        return view("admin/employees_management_index", compact('data'));
    }

    public function trains_index()
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        return view("admin/trains_management_index", compact('data'));
    }

    public function insert_train(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:trains',
            'type' => 'required',
            'no_of_cars' => 'required'
        ]);
        $train = new Train();
        $train->number = $request->number;
        $train->no_of_cars = $request->no_of_cars;
        $train->type = $request->type;
        $train->status = $request->status;
        $result = $train->save();
        if ($result) {
            return back()->with('success', 'Train Saved');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function insert_train_type(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:train_types',
        ]);
        $train_type = new Train_type();
        $train_type->name = $request->name;
        $result = $train_type->save();
        if ($result) {
            return back()->with('success', 'Train Type Saved');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function edit_train_index(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $train_id = $request->train_id;
        $train = Train::where('id', '=', $train_id)->first();
        return view('admin/edit_train', compact('data', 'train'));
    }

    public function edit_train(Request $request)
    {
        $request->validate([
            'number' => 'required|min:3',
            'type' => 'required',
            'no_of_cars' => 'required'
        ]);
        $train = Train::where('id', '=', $request->train_id)->first();
        $tmp = Train::where('number', '=', $request->number)->first();
        if ($tmp->id != $train->id) {
            return back()->with('fail', 'This number is already registered for another train');
        }
        $train->number = $request->number;
        $train->no_of_cars = $request->no_of_cars;
        $train->type = $request->type;
        $train->status = $request->status;
        $result = $train->save();
        if ($result) {
            return back()->with('success', 'Train Updated');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function delete_train(Request $request)
    {
        $train = Train::where('id', '=', $request->train_id)->first();
        $result = $train->delete();
        if ($result) {
            return redirect('admin/trains?view_all_trains')->with('success', 'Train Deleted');
        } else {
            return redirect('admin/trains?view_all_trains')->with('fail', 'Something went wrong');
        }
    }

    public function delete_train_type(Request $request){
        $train_type = Train_type::where('id', '=', $request->type_id)->first();
        $result = $train_type->delete();
        if ($result) {
            return redirect('admin/trains?view_train_types')->with('success', 'Train type Deleted');
        }
        return redirect('admin/trains?view_train_types')->with('fail', 'Something went wrong');
    }
}
