<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Contracts\Admin\UserServiceInterface;
use App\Exceptions\UserCannotBeDeletedException;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function create(array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'email_verified_at' => now()
        ];

        return User::create($userData);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->findById($id);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);
        
        return $user->fresh();
    }

    public function delete(int $id): void
    {
        $user = $this->findById($id);
        
        if ($user->enrollments()->exists()) {
            throw new UserCannotBeDeletedException(
                'Tidak dapat menghapus pengguna yang memiliki pendaftaran aktif',
                422
            );
        }

        $user->delete();
    }
}