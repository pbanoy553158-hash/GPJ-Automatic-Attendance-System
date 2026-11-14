<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {

        $user = $request->user();

        // Redirect unauthenticated users.
        if (!$user) {
            return redirect()->route('login');
        }

        // Prevent inactive users from accessing the system.
        if (!$user->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your account has been deactivated.',
                ]);
        }

        // Validate user role.
        if (
            !empty($roles) &&
            !in_array($user->role, $roles, true)
        ) {
            abort(403, 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}