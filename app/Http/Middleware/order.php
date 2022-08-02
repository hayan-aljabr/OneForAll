<?php

namespace App\Http\Middleware;

use App\Models\Order as Orders;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class order
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
        if(!auth()->check() && !auth()->user()->id == request()->get('orderid'))
        {
          dd("you are not allowed to see this");
        }
        return $next($request);

      /*  $user_id = auth()->user()->id;
       $order_id  = $order->id;
        if( $user_id !== $order_id)
        {
            return $next($request);
        }
        else{
            return response('not allowed to take this action', 500);
        }*/



    }
}
