<?php

namespace App\Interfaces;

interface AuthRepositoryInterface
{
    public function storeUser(array $userData);
    public function getUserByEmail($email);
}
