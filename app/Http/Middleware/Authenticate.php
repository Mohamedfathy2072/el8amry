<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            abort(response()->json(['error' => 'Unauthenticated'], 401));
        }

        if ($request->is('admin/*')) {
            return route('admin.login');
        }

        // لو مفيش غير الأدمن بس، خلاص بترجع admin.login
        return route('admin.login');
    }


}
