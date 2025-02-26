<?php

namespace Core\Auth;

interface AuthStrategy {
    public function authenticate();
    public function getUser();
}
