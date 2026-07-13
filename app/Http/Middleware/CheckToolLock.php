<?php

namespace App\Http\Middleware;

use App\Models\ToolLock;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckToolLock
{
    /**
     * Handle an incoming request — block access if the tool is locked.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();
        $routeName = $route ? $route->getName() : null;

        // Only check named routes that match tool patterns (e.g. merge.index, compress.process)
        if ($routeName) {
            $toolLock = ToolLock::where('tool_route', $routeName)->first();
            if ($toolLock && $toolLock->is_locked) {
                return redirect()->route('fitur')->with('error', "Alat ini sedang dikunci oleh admin.");
            }
        }

        return $next($request);
    }
}
