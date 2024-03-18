<?php

namespace App\Http\Middleware;

use Closure;

class CheckMaster
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
        if (session('level') !== 'Master') {
            return redirect('/'); // Redirect hoặc xử lý khi tài khoản không hợp lệ
        }
        return $next($request);
    }
}