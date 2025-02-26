<?php

namespace Core\Auth;
use Illuminate\Http\Request;
use Core\Auth\AuthStrategy;

class AuthToken implements AuthStrategy
{
    public function __construct(Request $request) {

    }
    public function authenticate() {
        return true;
    }
    public function getUser() {}
}
