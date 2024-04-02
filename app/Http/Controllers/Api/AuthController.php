<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use Spatie\FlareClient\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    public function signUp(SignUpRequest $request): JsonResponse
    {
        $userData = $request->validated();
        $user = $this->authRepository->storeUser($userData);
        if($user)
        {
            $user->password = Hash::make($request->password);
            $user->save();
            $token = $user->createToken("API TOKEN FOR " . $user->name);
            $result = [
                'user' => $user,
                'token' => $token->plainTextToken
            ];
            $user->sendEmailVerificationNotification();
            return ApiResponseClass::sendResponse($result,'User Registered Successfully!');
        }
        return ApiResponseClass::throw('Something Went Wrong!',500);


    }
    public function login(Request $request): JsonResponse
    {
        dd($request->all());
    }
}
