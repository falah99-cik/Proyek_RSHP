<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        $userRoles = $user->roles->pluck('nama_role')->toArray();

        Log::info("Checking user roles", [
            'user_id' => $user->iduser,
            'user_roles' => $userRoles,
            'required_roles' => $roles,
        ]);

        if (empty($roles)) {
            return $next($request);
        }

        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
