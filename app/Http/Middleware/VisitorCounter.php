<?php

namespace App\Http\Middleware;

use Closure;
use App\VisitorCount;
class VisitorCounter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $vc = VisitorCount::where('date',date('Y-m-d'))->first();
        if($vc){
          $vc->count +=1;
          $vc->save();
        }else{
          $vc = new VisitorCount;
          $vc->date=date('Y-m-d');
          $vc->save();
        }
        return $next($request);
    }
}
