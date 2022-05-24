<?php

namespace App\Http\Middleware;

use Closure;

class Alreadyloggedin_employee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(session()->has('employeeloginID')){
            return redirect('employee/home');
        }
        return $next($request);
    }
}