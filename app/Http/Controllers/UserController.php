<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Trip;
use Illuminate\Http\Request;
use App\User;
use App\Ticket;
use App\Line;
use App\Stops_stations;
use App\Booked_tickets;
use Hash;
use PDF;


use MongoDB\Driver\Session;


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

    public function user_book_index(){
        return view("user/book");
    }

    public function user_cancel_trip(){

        $user = Booked_tickets::where('user_id','=',session()->get("loginID"))->first();
        $result = $user->delete();
        if ($result) {
            return back()->with('Ticket Cancelled');
        } else {
            back()->with('fail','something went wrong');
        }
    }



//NOT FINAL FUNCTIONALITY FOR RESCHDULE
//we didnot put user id in booked tickets table



    public function user_reschedule_trip(Request $req){

        /*$userId=session()->get("userID");
        $booked_tickets= DB::table('booked_tickets')
         ->join('tickets','Booked_tickets.ticket','=','tickets.id')
         ->where('Booked_tickets.user',$userId)
         ->get();
        return view("user/reschedule_trip",['booked_tickets'=>$booked_tickets]);*/

        return view("user/reschedule_trip");
    }






    public function user_view_booked_trips(Request $req){

        $userId=session()->get('loginID');
        $book= booked_tickets::where('user_id',$userId)->get();
        return view("user/view_booked_trips",compact('book'));

    }



    public function show_available_trips(Request $req){

        $filter = $req->validate([
            'source'=>'string',
            'dest'=>'string',
        ]);

        if($req->isMethod('post'))
        {

            $source_station = $req->get('source');
            $destination_station = $req->get('dest');

            $query=Stops_stations::where('source_station','like','%'.$source_station.'%')
                ->where('destination_station','like','%'.$destination_station.'%')
                ->get();
        }

        return view('user/view_available_trips', compact('query'));


    }


    public function user_checkout(){

        return view("user/checkout");
    }

    /*
    public function user_seat(Request $req){

        $data=new booked_tickets;

        $data->seat=$req->seat;
        $data->ticket=$req->seat;

        if(Auth::id())
        {

            $data->user_id=Auth::user->id;

        }

        $data->save();

    }
    */

    public function generate_ticket(){

        $userId= Auth::user()->id;


        $data = [
            'heading' => 'Welcome to Funda of Web IT',
            'description' => 'This description of Funda of Web IT'
        ];
        $pdf = PDF::loadView('user/ticket', $data);

        return $pdf->download('Ticket.pdf');

        return view("user/ticket");
    }


}
