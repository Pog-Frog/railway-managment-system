<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Can_stop;
use App\Station;
use App\Train;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function trains_index()
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        return view("admin/trains_management_index", compact('data'));
    }

    public function insert_train(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:trains|min:3',
            'train_model' => 'required',
            'no_of_cars' => 'required',
            'admin' => 'required',
            'status' => 'required'
        ]);
        $train = new Train();
        $train->number = $request->number;
        $train->train_model = $request->train_model;
        $train->no_of_cars = $request->no_of_cars;
        $train->status = $request->status;
        if ($request->admin != "null") {
            $train->admin = $request->admin;
        }
        $result = $train->save();
        if ($result) {
            return back()->with('success', 'Train Saved');
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
            'train_model' => 'required',
            'no_of_cars' => 'required',
            'admin' => 'required',
            'status' => 'required'
        ]);
        $train = Train::where('id', '=', $request->train_id)->first();
        $tmp = Train::where('number', '=', $request->number)->first();
        if ($tmp) {
            if ($tmp->id != $train->id) {
                return back()->with('fail', 'This number is already registered for another train');
            }
        }
        $train->number = $request->number;
        $train->train_model = $request->train_model;
        $train->no_of_cars = $request->no_of_cars;
        $train->status = $request->status;
        if ($request->admin != "null") {
            $train->admin = $request->admin;
        }
        else{
            $train->admin = null;
        }
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
            return redirect('admin/trains/view_trains')->with('success', 'Train Deleted');
        } else {
            return redirect('admin/trains/view_trains')->with('fail', 'Something went wrong');
        }
    }

    public function view_trains(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        return view("admin/view_trains", compact('data'));
    }

    public function search_trains(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $user_query = $request->search_query;
        if($user_query == null){
            return view("admin/view_trains", compact('data'));
        }
        $result = Train::query()
            ->where('number', 'LIKE', "%{$user_query}%")
            ->orWhere('id', 'LIKE', "%{$user_query}%")
            ->orWhere('status', 'LIKE', "%{$user_query}%")
            ->orWhere('train_model', 'LIKE', "%{$user_query}%")
            ->orWhere('no_of_cars', 'LIKE', "%{$user_query}%")
            ->get();
        if ($result->isEmpty()) {
            $admin= Admin::where('name', '=', $user_query)->first();
            if ($admin) {
                $result = Train::query()
                    ->where('admin', 'LIKE', "%{$admin->id}%")
                    ->get();
            }
        }
        return view("admin/view_trains", compact('data', 'result'));
    }

    public function stations_index()
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        return view("admin/stations_managment_index", compact('data'));
    }

    public function insert_station(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:stations',
            'city' => 'required',
            'admin' => 'required'
        ]);
        $station = new Station();
        $station->name = $request->name;
        $station->city = $request->city;
        if ($request->admin != "null") {
            $station->admin = $request->admin;
        }

        $result = $station->save();
        if ($result) {
            return back()->with('success', 'Station Saved');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function edit_station_index(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $station_id = $request->station_id;
        $station = Station::where('id', '=', $station_id)->first();
        return view('admin/edit_station', compact('data', 'station'));
    }

    public function edit_station(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'city' => 'required',
            'admin' => 'required'
        ]);
        $station = Station::where('id', '=', $request->station_id)->first();
        $tmp = Station::where('name', '=', $request->name)->first();
        if ($tmp) {
            if ($tmp->id != $station->id) {
                return back()->with('fail', 'This number is already registered for another station');
            }
        }
        $station->name = $request->name;
        $station->city = $request->city;
        if ($request->admin != "null") {
            $station->admin = $request->admin;
        }
        else{
            $station->admin = null;
        }
        $result = $station->save();
        if ($result) {
            return back()->with('success', 'Station Updated');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function delete_station(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first(); ############
        $station = Station::where('id', '=', $request->station_id)->first();
        $result = $station->delete();
        if ($result) {
            return redirect('admin/stations/view_stations')->with('success', 'Station Deleted');
        }
        return redirect('admin/stations/view_stations')->with('fail', 'Something went wrong');
    }

    public function view_stations(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        return view("admin/view_stations", compact('data'));
    }

    public function search_stations(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $user_query = $request->search_query;
        if($user_query == null){
            return view("admin/view_stations", compact('data'));
        }
        $result = Station::query()
            ->where('name', 'LIKE', "%{$user_query}%")
            ->orWhere('city', 'LIKE', "%{$user_query}%")
            ->get();
        if ($result->isEmpty()) {
            $admin= Admin::where('name', '=', $user_query)->first();
            if ($admin) {
                $result = Station::query()
                    ->where('admin', 'LIKE', "%{$admin->id}%")
                    ->get();
            }
        }
        return view("admin/view_stations", compact('data', 'result'));
    }

    public function view_allowed_trains(Request $request){
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $station_id = $request->station_id;
        $station = Station::where('id', '=', $station_id)->first();
        $can_stop = Can_stop::query()
            ->where('station', '=',  $station_id)
            ->get();
        $temp_ids = [];
        foreach ($can_stop as $x){
            $temp_ids[] = $x->train;
        }
        $cannot_stop = Train::query()->whereNotIn('id', $temp_ids)->get();
        return view("admin/view_allowed_trains", compact('data', 'station', 'can_stop', 'cannot_stop'));
    }

    public function search_not_allowed_trains(Request $request){
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $station_id = $request->station_id;
        $station = Station::where('id', '=', $station_id)->first();
        $can_stop = Can_stop::query()
            ->where('station', '=',  $station_id)
            ->get();
        $temp_ids = [];
        foreach ($can_stop as $x){
            $temp_ids[] = $x->train;
        }
        $cannot_stop = Train::query()->whereNotIn('id', $temp_ids)->get();
        $user_query = $request->search_query;
        if($user_query == null){
            return view("admin/view_allowed_trains", compact('data', 'station', 'can_stop', 'cannot_stop'));
        }
        $result = Train::query()
            ->where('number', 'LIKE', "%{$user_query}%")
            ->orWhere('id', 'LIKE', "%{$user_query}%")
            ->orWhere('status', 'LIKE', "%{$user_query}%")
            ->orWhere('train_model', 'LIKE', "%{$user_query}%")
            ->orWhere('no_of_cars', 'LIKE', "%{$user_query}%")
            ->whereNotIn('id', $temp_ids)
            ->get();
        if ($result->isEmpty()) {
            $admin= Admin::where('name', '=', $user_query)->first();
            if ($admin) {
                $result = Train::query()
                    ->where('admin', 'LIKE', "%{$admin->id}%")
                    ->whereNotIn('id', $temp_ids)
                    ->get();
            }
        }
        return view("admin/view_allowed_trains", compact('data', 'station', 'can_stop', 'cannot_stop', 'result'));
    }

    public function add_allowed_train(Request $request){
        $record = new Can_stop();
        $record->train = $request->train_id;
        $record->station = $request->station_id;
        $result = $record->save();
        if ($result) {
            return back()->with('success', 'Record Saved');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function remove_allowed_train(Request $request){
        $record = Can_stop::query()
            ->where('station', '=', $request->station_id)
            ->where('train', '=', $request->train_id);
        $result = $record->delete();
        if ($result) {
            return back()->with('remove_success', 'Train removed');
        }
        return back()->with('remove_fail', 'Something went wrong');
    }
}
