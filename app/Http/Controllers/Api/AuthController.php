<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\FlareClient\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\AuthRepositoryInterface;

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
            $this->authRepository->hashUserPassword($user, $userData['password']);

            $user->sendEmailVerificationNotification();
            return ApiResponseClass::sendResponse('Registered','User Registered Successfully! Please Check Your Mail for Verification.');
        }
        return ApiResponseClass::throw('Something Went Wrong!',500);


    }

    public function login(LoginRequest $request)
    {
        $userData = $request->validated();
        $user = $this->authRepository->getUserByEmail($userData['email']);
        if(!$user || ! Hash::check($userData['password'], $user->password)){
            return ApiResponseClass::throw('Wrong Credentials!', 401);
        }
        if(!$user->hasVerifiedEmail()){
            return ApiResponseClass::throw('This Email Address has NOT been Verified!', 401);
        }
        $token = $user->createToken("API TOKEN FOR " . $user->name);
        $result = [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
        return ApiResponseClass::sendResponse($result,'User Logged In Successfully!');


    }

    public function verify($id) {
        $user = $this->authRepository->getUserById($id);
        if(!$user->hasVerifiedEmail()){
            $user->markEmailAsVerified();
        }
        return redirect()->to('/');

    }

    public function resend(Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return ApiResponseClass::sendResponse('verified','Email has already been verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        return ApiResponseClass::sendResponse('verification link','Verification link has been resent to your email');
    }

}
