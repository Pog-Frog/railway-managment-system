<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Trip;
use Illuminate\Http\Request;
use App\User;
use App\Ticket;
use App\Train;
use App\Line;
use App\Stops_stations;
use App\Seat;
use App\Booked_tickets;
use Illuminate\Support\Facades\Hash;
use PDF;


class UserController extends Controller
{

    public function user_login_index()
    {
        return view("user/login");
    }

    public function user_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|max:12|min:8'
        ]);
        $user = User::where('email', '=', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $request->session()->put('loginID', $user->id);
            return redirect("/");
        } else {
            return back()->with('fail', 'Email or Password is incorrect');
        }
    }

    public function user_register_index()
    {
        return view("user/register");
    }

    public function user_register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|max:11|min:11',
            'gendre' => 'required',
            'password' => 'required|max:12|min:8'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->gendre = $request->gendre;
        $user->password = Hash::make($request->password);
        $result = $user->save();
        if ($result) {
            return back()->with('success', 'User Registered');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function user_index()
    {
        $data = array();
        if (session()->has('loginID')) {
            $data = User::where('id', '=', session()->get("loginID"))->first();
        }
        return view("user/home");
    }


    public function user_logout()
    {
        if (session()->has('loginID')) {
            session()->pull('loginID');
            return redirect("/");
        }
    }

    public function user_book_index()
    {
        return view("user/book");
    }

    public function user_cancel_trip()
    {
        $user = Booked_tickets::where('user_id', '=', session()->get("loginID"))->first();
        $nowDate = Carbon::now();
        $stops_station = Stops_stations::where('id', '=', $user->stops_station)->first();
        if ($user) {
            if ($stops_station->date > $nowDate) {
                $userr = Booked_tickets::where('user_id', '=', session()->get("loginID"))->first();
                $result = $userr->delete();
                return view("user/cancel");
            } else {
                return view("user/date");
            }
        } else {
            return redirect("user/home");
        }
    }

    public function user_contact()
    {
        return view("user/contact");
    }

    public function user_reschedule_trip(Request $req)
    {
        $user = Booked_tickets::where('user_id', '=', session()->get("loginID"))->first();
        $nowDate = Carbon::now();
        $userId = session()->get('loginID');
        if ($user) {
            $book = Booked_tickets::where('user_id', $userId)->first();
            $stops_station = Stops_stations::where('id', '=', $book->stops_station)->first();
            if ($stops_station->date > $nowDate) {
                $user->delete();
                return view("user/reschedule_trip", compact('book', 'stops_station'));
            } else {
                return view("user/date");
            }
        } else {
            return redirect("user/home");
        }
    }


    public function user_view_booked_trips(Request $req)
    {
        $user = Booked_tickets::where('user_id', '=', session()->get("loginID"))->first();
        if ($user) {
            $userId = session()->get('loginID');
            $book = Booked_tickets::where('user_id', $userId)->first();
            $stops_station = Stops_stations::where('id', '=', $book->stops_station)->first();
            return view("user/view_booked_trips", compact('book', 'stops_station'));
        } else {
            return redirect()->back();
        }
    }

    public function today_trips(Request $req)
    {
        $todayDate = Carbon::now()->format('Y-d-m');
        $query = Stops_stations::where('date', 'like', '%' . $todayDate . '%')->get();
        $user = Booked_tickets::where('user_id', '=', session()->get("loginID"))->first();
        return view('user/today_trips', compact('query', 'user'));
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
            $user = Booked_tickets::where('user_id', '=', session()->get("loginID"))->first();
        }
        return view('user/view_available_trips', compact('query', 'user'));
    }

    public function user_checkout(Request $request)
    {
        $stops_id = $request->stops_id;
        $stops_station = Stops_stations::where('id', '=', $stops_id)->first();
        return view('user/checkout', compact('stops_id', 'stops_station'));
    }

    public function generate_ticket(Request $request)
    {
        $userId = session()->get('loginID');
        $stops_id = $request->stops_id;
        $booked_ticket = new Booked_tickets();
        $stops_station = Stops_stations::where('id', '=', $stops_id)->first();
        $train = Train::where('train_no', '=', $stops_station->train_no)->first();
        $seat = Seat::where('train', '=', $train->id)->where('seat_availability', '=', 'true')->first();
        $booked_ticket->user_id = $userId;
        $booked_ticket->stops_station = $stops_station->id;
        $booked_ticket->seat = $seat->id;
        $booked_ticket->save();
        $pdf = PDF::loadView('user/ticket', compact('booked_ticket', 'seat'));
        return $pdf->download('Ticket.pdf');
    }

    public function news_1()
    {
        return view('user/news_1');
    }

    public function news_2()
    {
        return view('user/news_2');
    }

    public function news_3()
    {
        return view('user/news_3');
    }

    public function submit()
    {
        return view('user/submit');
    }
}
