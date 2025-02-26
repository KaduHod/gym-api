<?php

namespace Tests\Feature;

use Tests\TestCase;


class AuthApiTest extends TestCase
{
    static $TEST_USER_CREDENTIAL_BASIC = "carlosjr.ribas@gmail.com:123456789";
    static function getTestUserCredentialBasic() {
        return base64_encode(self::$TEST_USER_CREDENTIAL_BASIC);
    }
    function teste_createUserRequest() {
        $request =  $this->post(
            uri: "/api/user",
            data: [
                "name" => "Carlos Ribas",
                "email" => "carlosjr.ribas@teste0.com",
                "password" => "123456789"
            ],
            headers: [
                "accept" => "application/json",
                "content-type" => "application/json",
            ]
        );
        $request->assertStatus(201);
    }
}
