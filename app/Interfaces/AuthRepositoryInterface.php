<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function storeUser(array $userData);
    public function hashUserPassword(User $user, $password);
    public function getUserByEmail($email);
    public function getUserById($id);
}
