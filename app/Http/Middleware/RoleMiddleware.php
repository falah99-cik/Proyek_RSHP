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

        // Jika relasi roles gagal, jadikan array kosong
        $userRoles = $user->roles ? $user->roles->pluck('nama_role')->toArray() : [];

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
