<?php
namespace App\Domain\TaskList\Repository;


use App\Domain\TaskList\Models\User\User;
use App\Domain\TaskList\Models\User\UserId;
use ArrayIterator;

interface UsersRepositoryInterface
{
    /**
     * @param UserId $userId
     * @return User
     */
    public function getUser(UserId $userId);

    public function saveUser(User $user);

    public function deleteUser(UserId $userId);

    /**
     * @param User $user
     * @return User
     */
    public function updateUser(User $user);

    /**
     * @return ArrayIterator|User[]
     */
    public function getAllUsers();
}
