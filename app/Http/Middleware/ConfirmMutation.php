<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Alte Aktionslinks bleiben erreichbar, führen über GET aber keine Änderung aus. */
class ConfirmMutation
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
            return response()->view('confirm_action', ['action' => $request->url()]);
        }
        return $next($request);
    }
}
