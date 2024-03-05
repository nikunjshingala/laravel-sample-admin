<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Subscribed
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
        if ($request->user() and ! $request->user()->subscribed('default'))
            //return redirect('show-subscription')->with(['status'=>'error','msg'=>trans('message.your_subscription_is_expired_or_you_do_not_have_active_plan_please_subscribe')]);

        return $next($request);
    }
}
