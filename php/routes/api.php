<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Joint;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateUserPostRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Movement;
use App\Models\MuscleGroup;
use App\Models\MusclePortion;

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
Route::middleware("auth:api")->group(function() {
    Route::get("/joints", function(Request $request) {
        $joints = Joint::select("id", "name")->paginate(100);
        return ApiResponse::success(
            data: $joints->items(),
            meta: [
                "has_next" => $joints->hasMorePages(),
                "total_items" => $joints->total(),
                "page_size" => $joints->perPage(),
                "page" => $joints->currentPage(),
            ]
        );
    });
    Route::get("/movements", function() {
        $collection = Movement::select("id", "name")->paginate(100);
        return ApiResponse::success(
            data: $collection->items(),
            meta: [
                "has_next" => $collection->hasMorePages(),
                "total_items" => $collection->total(),
                "page_size" => $collection->perPage(),
                "page" => $collection->currentPage(),
            ]
        );
    });
    Route::get("/muscle-groups", function() {
        $collection = MuscleGroup::select("id", "name")->paginate(100);
        return ApiResponse::success(
            data: $collection->items(),
            meta: [
                "has_next" => $collection->hasMorePages(),
                "total_items" => $collection->total(),
                "page_size" => $collection->perPage(),
                "page" => $collection->currentPage(),
            ]
        );
    });
    Route::get("/muscle-portions", function() {
        $collection = MusclePortion::select("id", "name")->paginate(100);
        return ApiResponse::success(
            data: $collection->items(),
            meta: [
                "has_next" => $collection->hasMorePages(),
                "total_items" => $collection->total(),
                "page_size" => $collection->perPage(),
                "page" => $collection->currentPage(),
            ]
        );
    });
});
