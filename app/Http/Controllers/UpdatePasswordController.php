<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\NewPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;


class UpdatePasswordController extends Controller
{
    public function update(NewPasswordRequest $request): JsonResponse
    {
        try {
            $user = \App\Models\User::find(Auth::id());
            $user->update(['password'=> Hash::make($request->new_password)]);

            return $this->success(
                data: null,
                message: 'Lozinka je uspešno promenjena!',
                statusCode: 200
            );

        } catch (Exception $e) {
            return $this->error(
                message: 'Došlo je do greške pri promeni lozinke: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }
}
