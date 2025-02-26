<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;
use Core\Auth\AuthException;
use Core\Auth\AuthService;

/*
*/
class AuthenticationMiddleware
{
    public function __construct(private AuthService $apiAuthService) {}
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = $this->apiAuthService->run($request);
            Context::add("user", $user);
            return $next($request);
        } catch (AuthException $th) {
            return response()->json([ "message" => $th->getMessage() ], 401)->send();
        } catch (\Throwable $th) {
            return response()->json([ "message" => $th->getMessage() ], 500)->send();
        }
    }
}
