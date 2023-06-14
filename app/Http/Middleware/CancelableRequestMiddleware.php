<?php

namespace App\Http\Middleware;

use App\Models\Request as RequestModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CancelableRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestModel = $request->route()->parameter('requestModel');
        if (!in_array($requestModel->status, [RequestModel::STATUS_REGISTERED, RequestModel::STATUS_ACCEPTED])) {
            abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized action.');
        }
        return $next($request);
    }
}
