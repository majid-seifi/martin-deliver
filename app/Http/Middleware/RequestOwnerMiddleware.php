<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestOwnerMiddleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected Guard $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, $field = 'intermediary_id'): Response
    {
        $requestModel = $request->route()->parameter('requestModel');
        if ($requestModel->{$field} !== $this->auth->user()->id) {
            abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized action.');
        }
        return $next($request);
    }
}
