<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Assigned_Trains_for_Lines;
use App\Can_stop;
use App\Captain;
use App\Employee;
use App\Mail\AdminEmail;
use App\Station;
use App\Technician;
use App\Train;
use App\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'line' => 'required',
            'captain' => 'required',
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
        if ($request->line != "null" && $request->captain != "null") {
            $assinged_line_for_train = new Assigned_Trains_for_Lines();
            $assinged_line_for_train->train = $train->id;
            if ($request->admin != "null") {
                $assinged_line_for_train->admin = $train->admin;
            }
            $assinged_line_for_train->captain = $request->captain;
            $assinged_line_for_train->line = $request->line;
            $result2 = $assinged_line_for_train->save();
            if ($result && $result2) {
                return back()->with('success', 'Train Saved');
            } else {
                return back()->with('fail', 'Something went wrong');
            }
        } elseif ($request->line != "null" && $request->captain == "null") {
            $assigned_line_for_train = new Assigned_Trains_for_Lines();
            $assigned_line_for_train->train = $train->id;
            if ($request->admin != "null") {
                $assigned_line_for_train->admin = $train->admin;
            }
            $assigned_line_for_train->line = $request->line;
            $result2 = $assigned_line_for_train->save();
            if ($result && $result2) {
                return back()->with('success', 'Train Saved');
            } else {
                return back()->with('fail', 'Something went wrong');
            }
        } elseif ($request->line == "null" && $request->captain != "null") {
            $assigned_line_for_train = new Assigned_Trains_for_Lines();
            $assigned_line_for_train->train = $train->id;
            if ($request->admin != "null") {
                $assigned_line_for_train->admin = $train->admin;
            }
            $assigned_line_for_train->captain = $request->captain;
            $result2 = $assigned_line_for_train->save();
            if ($result && $result2) {
                return back()->with('success', 'Train Saved');
            } else {
                return back()->with('fail', 'Something went wrong');
            }
        } else {
            if ($result) {
                return back()->with('success', 'Train Saved');
            } else {
                return back()->with('fail', 'Something went wrong');
            }
        }

    }

    public function edit_train_index(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $train_id = $request->train_id;
        $train = Train::where('id', '=', $train_id)->first();
        $assigned_line_for_train = Assigned_Trains_for_Lines::where('train', '=', $train_id)->first();
        return view('admin/edit_train', compact('data', 'train', 'assigned_line_for_train'));
    }

    public function edit_train(Request $request)
    {
        $request->validate([
            'number' => 'required|min:3',
            'train_model' => 'required',
            'no_of_cars' => 'required',
            'admin' => 'required',
            'line' => 'required',
            'captain' => 'required',
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
        } else {
            $train->admin = null;
        }
        $result = $train->save();
        $assigned_line_for_train = Assigned_Trains_for_Lines::query()->where('train', '=', $request->train_id)->first();
        if (empty($assigned_line_for_train)) {
            if ($request->captain == "null" && $request->line == "null") {
                if ($result) {
                    return back()->with('success', 'Train Saved');
                } else {
                    return back()->with('fail', 'Something went wrong');
                }
            }
            $assigned_line_for_train = new Assigned_Trains_for_Lines();
        }
        $assigned_line_for_train->train = $train->id;
        if ($request->admin != "null") {
            $assigned_line_for_train->admin = $train->admin;
        } else {
            $assigned_line_for_train->admin = null;
        }
        $assigned_line_for_train->captain = $request->captain;
        $assigned_line_for_train->line = $request->line;
        if ($request->captain == "null") {
            $assigned_line_for_train->captain = null;
        }
        if ($request->line == "null") {
            $assigned_line_for_train->line = null;
        }
        $result2 = $assigned_line_for_train->save();
        if ($result && $result2) {
            return back()->with('success', 'Train Saved');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function delete_train(Request $request)
    {
        $train = Train::where('id', '=', $request->train_id)->first();
        $assigned_line_for_train = Assigned_Trains_for_Lines::where('train', '=', $request->train_id)->first();
        $result = $train->delete();
        if (!empty($assigned_line_for_train)) {
            $result2 = $assigned_line_for_train->delete();
            if ($result && $result2) {
                return redirect('admin/trains/view_trains')->with('success', 'Train Deleted');
            } else {
                return redirect('admin/trains/view_trains')->with('fail', 'Something went wrong');
            }
        } else {
            if ($result) {
                return redirect('admin/trains/view_trains')->with('success', 'Train Deleted');
            } else {
                return redirect('admin/trains/view_trains')->with('fail', 'Something went wrong');
            }
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
        if ($user_query == null) {
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
            $admin = Admin::where('name', '=', $user_query)->first();
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
        } else {
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
        if ($user_query == null) {
            return view("admin/view_stations", compact('data'));
        }
        $result = Station::query()
            ->where('name', 'LIKE', "%{$user_query}%")
            ->orWhere('city', 'LIKE', "%{$user_query}%")
            ->get();
        if ($result->isEmpty()) {
            $admin = Admin::where('name', '=', $user_query)->first();
            if ($admin) {
                $result = Station::query()
                    ->where('admin', 'LIKE', "%{$admin->id}%")
                    ->get();
            }
        }
        return view("admin/view_stations", compact('data', 'result'));
    }

    public function view_allowed_trains(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $station_id = $request->station_id;
        $station = Station::where('id', '=', $station_id)->first();
        $can_stop = Can_stop::query()
            ->where('station', '=', $station_id)
            ->get();
        $temp_ids = [];
        foreach ($can_stop as $x) {
            $temp_ids[] = $x->train;
        }
        $cannot_stop = Train::query()->whereNotIn('id', $temp_ids)->get();
        return view("admin/view_allowed_trains", compact('data', 'station', 'can_stop', 'cannot_stop'));
    }

    public function search_not_allowed_trains(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $station_id = $request->station_id;
        $station = Station::where('id', '=', $station_id)->first();
        $can_stop = Can_stop::query()
            ->where('station', '=', $station_id)
            ->get();
        $temp_ids = [];
        foreach ($can_stop as $x) {
            $temp_ids[] = $x->train;
        }
        $cannot_stop = Train::query()->whereNotIn('id', $temp_ids)->get();
        $user_query = $request->search_query;
        if ($user_query == null) {
            return view("admin/view_allowed_trains", compact('data', 'station', 'can_stop', 'cannot_stop'));
        }
        $result = Train::query()
            ->whereNotIn('id', $temp_ids)
            ->where('number', 'LIKE', "%{$user_query}%")
            ->orWhere('id', 'LIKE', "%{$user_query}%")
            ->orWhere('status', 'LIKE', "%{$user_query}%")
            ->orWhere('train_model', 'LIKE', "%{$user_query}%")
            ->orWhere('no_of_cars', 'LIKE', "%{$user_query}%")
            ->get();
        if ($result->isEmpty()) {
            $admin = Admin::where('name', '=', $user_query)->first();
            if ($admin) {
                $result = Train::query()
                    ->where('admin', 'LIKE', "%{$admin->id}%")
                    ->whereNotIn('id', $temp_ids)
                    ->get();
            }
        }
        return view("admin/view_allowed_trains", compact('data', 'station', 'can_stop', 'cannot_stop', 'result'));
    }

    public function add_allowed_train(Request $request)
    {
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

    public function remove_allowed_train(Request $request)
    {
        $record = Can_stop::query()
            ->where('station', '=', $request->station_id)
            ->where('train', '=', $request->train_id);
        $result = $record->delete();
        if ($result) {
            return back()->with('remove_success', 'Train removed');
        }
        return back()->with('remove_fail', 'Something went wrong');
    }

    public function employees_index()
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        return view("admin/employees_management_index", compact('data'));
    }

    public function insert_employee_index(Request $request)
    {
        $choice = $request->type;
        if ($choice == "captain") {
            return redirect('admin/employees?insert_captain_index');
        } elseif ($choice == "technician") {
            return redirect('admin/employees?insert_technician_index');
        } else {
            return redirect('admin/employees?insert_reservation_employee_index');
        }
    }

    public function insert_captain(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:captains'
        ]);
        $captain = new Captain();
        $captain->name = $request->name;
        $captain->email = $request->email;
        $rand_pass = Str::random(12);
        $captain->password = Hash::make($rand_pass);
        $result = $captain->save();
        $details = ['title' => 'Admin notification @railway MS', 'name' => $captain->name, 'body' => 'This email contains your password for your work account', 'content' => $rand_pass];
        $subject = "Password Notification";
        \Mail::to($captain->email)->send(new AdminEmail($details, $subject));
        if ($result) {
            return back()->with('success', 'Employee registered and an email has been sent with the user password')->with('profession', $request->profession);
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function insert_technician(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:technicians'
        ]);
        $technician = new Technician();
        $technician->name = $request->name;
        $technician->email = $request->email;
        $rand_pass = Str::random(12);
        $technician->password = Hash::make($rand_pass);
        $result = $technician->save();
        $details = ['title' => 'Admin notification @railway MS', 'name' => $technician->name, 'body' => 'This email contains your password for your work account', 'content' => $rand_pass];
        $subject = "Password Notification";
        \Mail::to($technician->email)->send(new AdminEmail($details, $subject));
        if ($result) {
            return back()->with('success', 'Employee registered and an email has been sent with the user password')->with('profession', $request->profession);
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function insert_reservation_employee(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees'
        ]);
        $emp = new Employee();
        $emp->name = $request->name;
        $emp->email = $request->email;
        $rand_pass = Str::random(12);
        $emp->password = Hash::make($rand_pass);
        $result = $emp->save();
        $details = ['title' => 'Admin notification @railway MS', 'name' => $emp->name, 'body' => 'This email contains your password for your work account', 'content' => $rand_pass];
        $subject = "Password Notification";
        \Mail::to($emp->email)->send(new AdminEmail($details, $subject));
        if ($result) {
            return back()->with('success', 'Employee registered and an email has been sent with the user password')->with('profession', $request->profession);
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function view_employees()
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        return view("admin/view_employees", compact('data'));
    }

    public function search_employees(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        if ($request->profession == "captain") {
            $user_query = $request->search_query;
            $result = Captain::query()
                ->where('name', 'LIKE', "%{$user_query}%")
                ->orWhere('email', 'LIKE', "%{$user_query}%")
                ->get();
            $index = 1;
        } elseif ($request->profession == "technician") {
            $user_query = $request->search_query;
            $result = Technician::query()
                ->where('name', 'LIKE', "%{$user_query}%")
                ->orWhere('email', 'LIKE', "%{$user_query}%")
                ->get();
            $index = 2;
        } else {
            $user_query = $request->search_query;
            $result = Employee::query()
                ->where('name', 'LIKE', "%{$user_query}%")
                ->orWhere('email', 'LIKE', "%{$user_query}%")
                ->get();
            $index = 3;
        }
        return view("admin/view_employees", compact('data', 'result', 'index'));
    }

    public function edit_captain_index(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $emp = Captain::where('id', '=', $request->emp_id)->first();
        $profession = "captain";
        return view('admin/edit_employee', compact('data', 'profession', 'emp'));

    }

    public function edit_technician_index(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $emp = Technician::where('id', '=', $request->emp_id)->first();
        $profession = "technician";
        return view('admin/edit_employee', compact('data', 'profession', 'emp'));
    }

    public function edit_reservation_employee_index(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $emp = Employee::where('id', '=', $request->emp_id)->first();
        $profession = "reservation";
        return view('admin/edit_employee', compact('data', 'profession', 'emp'));
    }

    public function edit_employee(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);
        if ($request->profession == "captain") {
            $captain = Captain::where('id', '=', $request->emp_id)->first();
            $tmp = Captain::where('name', '=', $request->name)->first();
            if ($tmp) {
                if ($tmp->id != $captain->id) {
                    return back()->with('fail', 'This name is already registered for another employee');
                }
            }
            $captain->name = $request->name;
            $captain->email = $request->email;
            $result = $captain->save();
            if ($result) {
                return back()->with('success', 'Employee Updated');
            } else {
                return back()->with('fail', 'Something went wrong');
            }
        } elseif ($request->profession == "technician") {
            $technician = Technician::where('id', '=', $request->emp_id)->first();
            $tmp = Technician::where('name', '=', $request->name)->first();
            if ($tmp) {
                if ($tmp->id != $technician->id) {
                    return back()->with('fail', 'This name is already registered for another employee');
                }
            }
            $technician->name = $request->name;
            $technician->email = $request->email;
            $result = $technician->save();
            if ($result) {
                return back()->with('success', 'Employee Updated');
            } else {
                return back()->with('fail', 'Something went wrong');
            }
        } else {
            $reservation_emp = Employee::where('id', '=', $request->emp_id)->first();
            $tmp = Employee::where('name', '=', $request->name)->first();
            if ($tmp) {
                if ($tmp->id != $reservation_emp->id) {
                    return back()->with('fail', 'This name is already registered for another employee');
                }
            }
            $reservation_emp->name = $request->name;
            $reservation_emp->email = $request->email;
            $result = $reservation_emp->save();
            if ($result) {
                return back()->with('success', 'Employee Updated');
            } else {
                return back()->with('fail', 'Something went wrong');
            }
        }
    }

    public function send_emp_password(Request $request)
    {
        $rand_pass = Str::random(12);
        $subject = "Password Notification";
        if ($request->profession == "captain") {
            $captain = Captain::where('id', '=', $request->emp_id)->first();
            $captain->password = Hash::make($rand_pass);
            $result = $captain->save();
            $details = ['title' => 'Admin notification @railway MS', 'name' => $captain->name, 'body' => 'This email contains your newly generated password for your work account', 'content' => $rand_pass];
            \Mail::to($captain->email)->send(new AdminEmail($details, $subject));
        } elseif ($request->profession == "technician") {
            $technician = Technician::where('id', '=', $request->emp_id)->first();
            $technician->password = Hash::make($rand_pass);
            $result = $technician->save();
            $details = ['title' => 'Admin notification @railway MS', 'name' => $technician->name, 'body' => 'This email contains your newly generated password for your work account', 'content' => $rand_pass];
            \Mail::to($technician->email)->send(new AdminEmail($details, $subject));
        } else {
            $reservation_emp = Employee::where('id', '=', $request->emp_id)->first();
            $reservation_emp->password = Hash::make($rand_pass);
            $result = $reservation_emp->save();
            $details = ['title' => 'Admin notification @railway MS', 'name' => $reservation_emp->name, 'body' => 'This email contains your newly generated password for your work account', 'content' => $rand_pass];
            \Mail::to($reservation_emp->email)->send(new AdminEmail($details, $subject));
        }
        if ($result) {
            return back()->with('success', 'An email has been sent with the user password');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function delete_employee(Request $request)
    {
        if ($request->profession == "captain") {
            $captain = Captain::where('id', '=', $request->emp_id)->first();
            $result = $captain->delete();
        } elseif ($request->profession == "technician") {
            $technician = Technician::where('id', '=', $request->emp_id)->first();
            $result = $technician->delete();
        } else {
            $reservation_emp = Employee::where('id', '=', $request->emp_id)->first();
            $result = $reservation_emp->delete();
        }
        if ($result) {
            return redirect('admin/employees/view_employees')->with('success', 'Employee Deleted');
        }
        return redirect('admin/employees/view_employees')->with('fail', 'Something went wrong');
    }

    public function lines_index()
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        return view("admin/lines_managment_index", compact('data'));
    }

    public function insert_line(Request $request)
    {
        $request->validate([
            'source_station' => 'required',
            'destination_station' => 'required'
        ]);
        if ($request->source_station == $request->destination_station) {
            return back()->with('fail', 'The Source station cannot be the same as the Destination station');
        }
        if ($request->source_station == "null" || $request->destination_station == "null") {
            return back()->with('fail', 'The Source station or the Destination station cannot be empty (None)');
        }
        $line = new Line();
        $line->source_station = $request->source_station;
        $line->destination_station = $request->destination_station;
        $result = $line->save();
        if ($result) {
            return back()->with('success', 'Line Saved');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function edit_line_index(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $line_id = $request->line_id;
        $line = Line::where('id', '=', $line_id)->first();
        return view('admin/edit_line', compact('data', 'line'));
    }

    public function edit_line(Request $request)
    {
        $request->validate([
            'source_station' => 'required',
            'destination_station' => 'required'
        ]);
        if ($request->source_station == $request->destination_station) {
            return back()->with('fail', 'The Source station cannot be the same as the Destination station');
        }
        if ($request->source_station == "null" || $request->destination_station == "null") {
            return back()->with('fail', 'The Source station or the Destination station cannot be empty (None)');
        }
        $line = Line::where('id', '=', $request->line_id)->first();
        $line->source_station = $request->source_station;
        $line->destination_station = $request->destination_station;
        $result = $line->save();
        if ($result) {
            return back()->with('success', 'Line Updated');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function delete_line(Request $request)
    {
        $line = Line::where('id', '=', $request->line_id)->first();
        $result = $line->delete();
        if ($result) {
            return redirect('admin/lines/view_lines')->with('success', 'Line Deleted');
        }
        return redirect('admin/lines/view_lines')->with('fail', 'Something went wrong');
    }

    public function view_lines(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        return view("admin/view_lines", compact('data'));
    }

    public function search_lines(Request $request)
    {
        $data = Admin::where('id', '=', session()->get("adminID"))->first();
        $user_query = $request->search_query;
        $result = Line::query()
            ->where('id', 'LIKE', "%{$user_query}%")
            ->orWhere('source_station', 'LIKE', "%{$user_query}%")
            ->orWhere('destination_station', 'LIKE', "%{$user_query}%")
            ->get();
        return view("admin/view_lines", compact('data', 'result'));
    }
}
