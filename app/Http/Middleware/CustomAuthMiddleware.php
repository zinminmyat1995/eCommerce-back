<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Retrieve the token from the request headers
        $token = $request->header('X-Custom-Token');

        // Verify the token (this is a placeholder; implement your own logic)
        if ($token !== 'Bearer UuWkjPict8iJ3OqsX7dlc4n5WglfJ6qQdQHA0wbw4a49ae76') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Proceed with the request
        return $next($request);
    }
}
