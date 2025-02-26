<?php

namespace Core\Auth;

use Core\Auth\AuthStrategy;
use Core\Auth\AuthException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthBasic implements AuthStrategy {
    public Request $request;
    private array $headerValue;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->headerValue = $this->getAuthorizationHeaderValue();
    }

    public function authenticate()
    {
        if(!($user = $this->getUser()))
            throw new AuthException("User from athentication not found!");

        $pwd = array_pop($this->headerValue);
        if(!Hash::check($pwd, $user->password))
            throw new AuthException("Unauthorized");
    }

    public function getUser()
    {
        return User::where("email", $this->headerValue[0])->first();
    }

    private function getAuthorizationHeaderValue(): array {
        $authorization = trim($this->request->header("authorization", ""));
        $authorization = trim(substr($authorization, 6));
        if(!($authorization = base64_decode($authorization))) {
            throw new AuthException("Invalid base64 encoding!");
        }
        if(!($exploded = explode(":", $authorization)) || count($exploded) != 2) {
            throw new AuthException("Invalid parameters for basic auth");
        }
        return $exploded;
    }
}
