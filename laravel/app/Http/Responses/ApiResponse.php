<?php
namespace App\Http\Responses;

// Create a class that represents the response of the API
// This class will be used to return the response of the API
// The class will have two methos, success and error
class ApiResponse {
    static function success($data, $meta = null, $message = "Success", $status = 200) {
        $content = [
            "message" => $message,
            "data" => $data,
        ];
        if($meta) {
            $content["meta"] = $meta;
        }
        return response()->json($content, $status);
    }
    static function error($message, $status = 400) {
        return response()->json([
            "message" => $message
        ], $status);
    }
}
