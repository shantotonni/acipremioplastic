<?php

namespace App\Http\Middleware;

use App\CustomerToken;
use Closure;
use App\UserToken;

class CustomAuth{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $token = str_replace("token ","",$request->header('Authorization'));

        $customer_token = CustomerToken::where('token',$token)->first();
        $user_token = UserToken::where('token',$token)->first();

        if(!$customer_token && !$user_token){
            return response()->json(['error'=>"Token authorization error"],401);
        }

        return $next($request);
    }
}
