<?php

namespace Tests\Feature;

use Tests\TestCase;


class AuthApiTest extends TestCase
{

    static $TEST_USER_CREDENTIAL_BASIC = "carlosjr.ribas@gmail.com:123456789";
    /**
     * A basic feature test example.
     */
    /*public function test_basicAuthentication(): void
    {
        $response = $this->get('/api/test', [
            "accept" => "application/json",
            "authorization" => "Basic ".base64_encode(AuthApiTest::$TEST_USER_CREDENTIAL_BASIC)
        ]);
        $response->assertStatus(200);
    }/*
    public function test_basicAuthenticationWrongHeader(): void
    {
        $response = $this->get('/api/test', [
            "accept" => "application/json",
            "authorization" => "Baic ".base64_encode(AuthApiTest::$TEST_USER_CREDENTIAL_BASIC)
        ]);
        $response->assertStatus(401);
    }
    public function test_getUserToken(): void
    {
        $response = $this->get(
        uri: '/api/auth/token',
        headers: [
            "accept" => "application/json",
            "authorization" => "Basic ".base64_encode(AuthApiTest::$TEST_USER_CREDENTIAL_BASIC)
        ]);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertNotEmpty($data["access_token"]);
    }*/
    public function test_tokenAuthentication() {
        $tokenRes = $this->get(
            uri: "/api/auth/token",
            headers: [
                "accept" => "application/json",
                "authorization" => "Basic ".base64_encode(AuthApiTest::$TEST_USER_CREDENTIAL_BASIC)
            ]
        );
        $tokenRes->assertStatus(200);
        $token = $tokenRes->json()["access_token"];
        $response = $this->get("/api/test", [
            "accept" => "application/json",
            "token" => $token
        ]);
        $response->assertStatus(200);
    }
}
