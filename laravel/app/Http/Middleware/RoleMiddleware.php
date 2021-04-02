<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $login = new LoginController;
        $user = $login->getAuthenticatedUser()->original['user'];
        if($user->role == 0){
            return $next($request);
        }
        return redirect()->route('unauthorized');
    }
}
