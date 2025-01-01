<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class PharmacyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        try{
            if(Auth::guard('pharmacy')->check())
                return $next($request);
        }
        catch(\Exception $e){
            return response()->json(["msg"=>"dlfg"]);
            dd($e->getMessage());
        }
        
        return redirect()->route('pharmacy.login');

    }
}
