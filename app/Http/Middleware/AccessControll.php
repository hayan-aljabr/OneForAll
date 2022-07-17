<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

class AccessControll
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {


      /*  if ( Auth::check() && Auth::user()->isAdmin() )
        {

        }

        return redirect('home');*/



        $user = auth()->user()->user_type;

        if($user == 'USR'){
           return response('not allowed to take this action', 500);
        }

        return $next($request);


    /*    $user = Auth::user();
            if($user){
                 return $next($request);
            }
            return response('not allowed to take this action', 500);*/




    /*    $user  = Auth::user();



        if (!Auth::check()) // I included this check because you have it, but it really should be part of your 'auth' middleware, most likely added as part of a route group.
          return "fuck off";

       $user = User;
       $user = Auth::user();

    if($user->isAdmin())
        return $next($request);
       /* if ($user->user_type == 'SADM') {

            return $next($request);
       }
       return "not admin";*/


        // $user = Auth::user();




         /*   $user = auth()->guard('api')->user()->user_type;

                    if($user != 'ADM')
                        return "Acess Denied";
             return $next($request);*/

      //  $user = Auth::user();

        /* $user_type = User::where('user_type','=','ADM')->get();

              if($user_type == null)
                  return "Acess Denied";*/
     //   $user_type = $user->user_type;

        //$premissiomName = $request->route()->getName();

    /*    if(! $user_type == "ADM" || "SADM")
            return "Acess Denied";

        return $next($request);*/



    }
}
