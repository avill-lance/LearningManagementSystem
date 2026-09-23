<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks Teacher/Student accounts still flagged with must_change_password
 * from reaching their dashboard (deep link, back button, bookmark) until
 * they change their password.
 */
class EnsurePasswordIsChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password && in_array($user->role, ['Teacher', 'Student'], true)) {
            return redirect()->route('password.change');
        }

        return $next($request);
    }
}
