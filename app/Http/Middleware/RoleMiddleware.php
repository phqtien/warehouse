<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        $roleUser = Role::where('user_id', $user->id)->first();

        if ($roleUser && in_array($roleUser->name, $roles)) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', "You don't have permission to access!");
    }
}
