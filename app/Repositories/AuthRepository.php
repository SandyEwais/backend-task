<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function storeUser(array $userData)
    {
        return User::create($userData);

    }
    public function getUserByEmail($email)
    {
        $user = User::where('email',$email)->first();
        return $user ? $user : null;

    }
}
