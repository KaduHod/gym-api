<?php

use App\Http\Requests\CreateUserPostRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Joint;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Auth;

Route::post('/user', function(CreateUserPostRequest $request) {
    $data = $request->only(["name", "email", "password"]);
    $user = User::factory()->create($data);
    $token = $user->createToken(name: $user->email)->accessToken;
    $user->save();
    return ApiResponse::success(
        data: ["token" => $token],
        message: "User created successfully",
        status: 201
    );
});
Route::post("/auth/token", function(LoginRequest $request) {
    $data = $request->only(["email", "password"]);
    if(!($user = User::where("email", $data["email"])->first())) {
        return ApiResponse::error(
            message: "User not found",
            status: 404
        );
    }
    if(!Auth::attempt($data)) {
        return ApiResponse::error(
            message: "Invalid credentials",
            status: 401
        );
    }
    return response(
        content:[
            "message" => "Success",
            "access_token" => $user->createToken(name: $user->email)->accessToken,
        ],
        status: 200
    );
});
Route::middleware("auth.api")->group(function() {
    Route::get("/joints", function(Request $request) {
        $joints = Joint::pluck("name");
        print_r($joints);die();
        return ApiResponse::success(
            data: $joints,
            message: "Success",
            meta: [
                "has_next" => false,
                "total_items" => $joints->count(),
                "page_size" => $joints->count(),
                "page" => 1
            ]
        );
    });
});
/*Route::middleware(AuthenticationMiddleware::class)->get("/test", function(Request $request) {
    return response(200);
})*/;
