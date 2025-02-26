<?php

namespace Core\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use Core\Auth\AuthBasic;
use Core\Auth\AuthException;
use Core\Auth\AuthJwt;
use Core\Auth\AuthToken;

class AuthService {
    public function run(Request $request): ?User {
        $authStrategy = match($this->getAuthType($request)) {
            "token" => new AuthToken($request),
            "bearer" => new AuthJwt($request),
            "basic" => new AuthBasic($request),
            default => throw new AuthException("Authentication type not supported")
        };
        $authStrategy->authenticate();
        return $authStrategy->getUser();
    }
    public function getAuthType(Request $request): string
    {
        if ($request->hasHeader("token"))
            return "token";

        $authorization = trim($request->header("authorization", ""));
        if (str_starts_with(strtolower($authorization), "basic"))
            return "basic";

        if (str_starts_with(strtolower($authorization), "bearer"))
            return "bearer";
        return "";
    }
}
