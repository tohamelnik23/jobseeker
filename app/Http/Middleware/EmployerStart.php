<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
class EmployerStart{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	function handle($request, Closure $next){  
		if (Auth::check() && (Auth::user()->role == 2) && (  Auth::user()->phone_verified_status == 1) ) {
			return $next($request);
		}
		else{
			return redirect()->route('employer.profile.start');
		}
	}
}