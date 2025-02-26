<?php

use App\Http\Requests\CreateUserPostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Joint;
use Illuminate\Support\Facades\Context;
use App\Http\Middleware\AuthenticationMiddleware;

Route::post('/user', function(CreateUserPostRequest $request) {
    $data = $request->validated();
    $user = User::factory()->create($data);
    $user->createToken(name: $user->email, expiresAt: (new DateTimeImmutable())->modify("+2 hour"));
    $user->save();
    return response()->json(
        data:[ "message" => "User created successfully" ],
        status: 201
    );
});
Route::middleware(AuthenticationMiddleware::class)->get("/joints", function(Request $request) {
    $joints = Joint::pluck("name");
    return response()->json([
        "message" => "Success",
        "data" => $joints,
        "meta" => [
            "has_next" => false,
            "total_items" => $joints->count(),
            "page_size" => $joints->count(),
            "page" => 1
        ]
    ]);
});
Route::middleware(AuthenticationMiddleware::class)->get("/auth/token", function(Request $request) {
    try {
        if(!($user = Context::get("user"))) {
            return response()->json(["message" => "Internal error. Contact the admin!"], 500);
        }
        $token = $user->tokens()->first();
        return response(
            content:[
                "message" => "Success",
                "access_token" => $token->token,
                "expires_at" => $token->expires_at
            ],
            status: 200
        );
    } catch (\Throwable $th) {
        return response()->json(["message" => $th->getMessage()], 400);
    }
});
Route::middleware(AuthenticationMiddleware::class)->get("/test", function(Request $request) {
    return response(200);
});
