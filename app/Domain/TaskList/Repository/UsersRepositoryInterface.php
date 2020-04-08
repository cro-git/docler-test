<?php
namespace App\Domain\TaskList\Repository;


use App\Domain\TaskList\Models\User\User;
use App\Domain\TaskList\Models\User\UserId;

interface UsersRepositoryInterface
{
    public function getUser(UserId $userId);

    public function saveUser(User $user);

    public function deleteUser(UserId $userId);

    public function updateUser(User $user);
}
