<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPluginActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $code): Response
    {
        if (!pluginActiveCheck($code)) {
            return redirect()->route('dashboard')->with('error', 'This plugin is not active.');
        }
        return $next($request);
    }
}
