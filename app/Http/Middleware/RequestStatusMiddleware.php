<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $status): Response
    {
        $requestModel = $request->route()->parameter('requestModel');
        if ($requestModel->status !== $status) {
            abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized action.');
        }
        return $next($request);
    }
}
