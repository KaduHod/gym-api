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
use Illuminate\Support\Facades\DB;
use Utils\General;

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
    Route::get("/mmj", function(Request $request) {
        $page = General::get("page", $request->query());
        if(intval($page) < 1) {
            return ApiResponse::error(
                message: "Invalid page number",
                status: 400
            );
        }
        $page = intval($page);;
        $pageSize = 10;
        $pageQuery = "LIMIT " . $pageSize . " OFFSET ".$pageSize * ($page);
        $query = <<<SQL
SELECT
    mg.name AS muscle_group_name,
    mg.id AS muscle_group_id,
    mp.name AS muscle_portion_name,
    mp.id AS muscle_portion_id,
    a.name AS joint_name,
    a.id AS joint_id,
    m.name AS movement_name,
    m.id AS movement_id
FROM articulation_movement_muscle amm
INNER JOIN movements m ON m.id = amm.movement_id
INNER JOIN articulations a ON a.id = amm.articulation_id
INNER JOIN muscle_portion mp ON mp.id = amm.muscle_portion_id
INNER JOIN muscle_group mg ON mp.muscle_group_id = mg.id
$pageQuery
SQL;
        $result = DB::select($query);
        $total = DB::select("SELECT COUNT(*) AS total FROM articulation_movement_muscle")[0]->total;
        $hasNext = $total > $pageSize * ($page + 1);
        return ApiResponse::success(
            data: $result,
            meta: [
                "page" => $page,
                "page_size" => $pageSize,
                "total_items" => $total,
                "has_next" => $hasNext,
            ]
        );
    });
});
