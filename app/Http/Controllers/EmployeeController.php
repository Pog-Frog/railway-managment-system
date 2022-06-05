<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Hash;
use App\Ticket;
use App\Train;
use App\Line;
use App\Stops_stations;
use App\Seat;
use App\Booked_tickets;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Trip;
use App\User;



class EmployeeController extends Controller
{
    //
    public function employee_login_index()
    {
        return view("employee/login");
    }

    public function employee_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|max:12|min:8'
        ]);
        $employee = Employee::where('email', '=', $request->email)->first();
        if ($employee && Hash::check($request->password, $employee->password)) {
            $request->session()->put('employeeloginID', $employee->id);
            return redirect("employee/home");
        } else {
            return back()->with('fail', 'Email or Password is incorrect');
        }
    }

    public function employee_book_index()
    {
        return view("employee/book");
    }

    public function employee_index()
    {
        $data = array();
        if (session()->has('employeeloginID')) {
            $data = Employee::where('id', '=', session()->get("employeeloginID"))->first();
        }
        return view("employee/home", compact('data'));
    }

    public function employee_logout()
    {
        if (session()->has('employeeloginID')) {
            session()->pull('employeeloginID');
            return redirect("employee/");
        }
    }

   
    public function show_available_trips(Request $req)
    {
        $filter = $req->validate([
            'source' => 'string',
            'dest' => 'string',
        ]);
        if ($req->isMethod('post')) {
            $source_station = $req->get('source');
            $destination_station = $req->get('dest');
            $query = Stops_stations::where('source_station', 'like', '%' . $source_station . '%')
                ->where('destination_station', 'like', '%' . $destination_station . '%')
                ->get();
            $employee = Booked_tickets::where('employee_id', '=', session()->get("employeeloginID"))->first();
        }
        return view('employee/view_available_trips');
        // , compact('query', 'employee'));
    }

    public function employee_checkout(Request $request){

        $stops_id = $request->stops_id;
        $stops_station = Stops_stations::where('id', '=', $stops_id)->first();
        return view('user/checkout', compact('stops_id', 'stops_station'));
        return view("employee/checkout");
    }


    public function generate_ticket_employee()
    {

        $employeeId = session()->get('employeeloginID');

        $data = [
            'heading' => 'Welcome to Funda of Web IT',
            'description' => 'This description of Funda of Web IT'
        ];
        $pdf = PDF::loadView('employee/ticket', $data);

        return $pdf->download('Ticket.pdf');

        return view("employee/ticket");
    }
}
