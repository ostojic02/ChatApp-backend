<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index():JsonResponse
    {
        $users = User::where('id', '!=', auth('api')->user()->id)->get();
        return $this->success($users);
    }
}
