<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LogInRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request):JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $token = $user->createToken(User::USER_TOKEN);

        return $this->success([
            'user'=>$user,
            'token'=>$token->plainTextToken], message:'User has been register');}

    // Login function
    public function login(LogInRequest $request):JsonResponse
    {
        $isValid = $this->isValidCredential($request);

        if (!$isValid['success']) {
            return $this->error($isValid['message'], statusCode: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    
        $user = $isValid['user'];
        $token = $user->createToken(User::USER_TOKEN);
    
        return $this->success([
            'user' => $user,
            'token' => $token->plainTextToken
        ], message: 'Login successfully!'); 
    }


    private function isValidCredential(LoginRequest $request):array{
    
    $data = $request->validated();
    $user = User::where('email', $data['email'])->first();

    if ($user === null) {
        return [
            'success' => false,
            'message' => 'Invalid Credential'
        ];
    }else if (Hash::check($data['password'], $user->password)) {
        return [
            'success' => true,
            'user' => $user
        ];
    }else{
        return [
            'success' => false,
            'message' => 'Password is not matched'];}
    }



    // Logout function
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(data: null, message: 'Logout successfully!');
    }
}
