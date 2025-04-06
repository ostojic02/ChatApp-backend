<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    use ValidatesRequests, AuthorizesRequests, DispatchesJobs;
    public function success(mixed $data, string $message = "ok", int $statusCode = 200): JsonResponse{
    return response()->json([
        'data' => $data,
        'success' => true,
        'message' => $message,
    ], $statusCode);}

    public function error(string $message, int $statusCode = 400): JsonResponse{
    return response()->json([
        'data' => null,
        'success' => false,
        'message' => $message,
    ], $statusCode);}
}
