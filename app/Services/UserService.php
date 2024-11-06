<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;

class UserService
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createUser(array $data): User
    {
        return $this->repository->create($data);
    }

    public function getAllUsers()
    {
        return $this->repository->all();
    }

    public function getUserById(int $id): ?User
    {
        return $this->repository->find($id);
    }

    public function updateUser(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
