<?php

namespace App\Contracts\Admin;

use App\Models\User;

interface UserServiceInterface
{
    public function findById(int $id): User;
    public function create(array $data): User;
    public function update(int $id, array $data): User;
    public function delete(int $id): void;
}