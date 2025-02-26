<?php

namespace Core\Auth;

use Illuminate\Http\Request;
use App\Core\Auth\AuthStrategy;

class AuthJwt implements AuthStrategy
{
    public function __construct(Request $request) {}
    public function authenticate() {}
    public function getUser() {}
}
