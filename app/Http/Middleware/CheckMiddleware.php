<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckMiddleware
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
        if(Auth::check())
        {
            //会员和管理员的话可以访问功能
            if(Auth::user()->identity === 'admin' || Auth::user()->identity === 'vip') {

                return $next($request);

            }else{

                //游客的话，检测体验时间是否过期
                if (Auth::user()->overdue_at > time()){

                    return $next($request);

                }else{

                    return redirect('/admin/service/help');

                }
            }

        }

        return $next($request);

    }
}
