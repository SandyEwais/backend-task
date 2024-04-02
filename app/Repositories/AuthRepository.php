<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\AuthRepositoryInterface;

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

    public function hashUserPassword(User $user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
    }

    public function getUserByEmail($email)
    {
        return User::where('email',$email)->first();

    }
    public function getUserById($id)
    {
        return User::where('id',$id)->first();

    }
}
