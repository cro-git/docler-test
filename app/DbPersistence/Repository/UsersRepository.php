<?php

namespace App\DbPersistence\Repository;

use App\DbPersistence\Models\User;
use App\DbPersistence\Mutator\UserMutator;
use App\Domain\TaskList\Models\User\User as Domainuser;
use App\Domain\TaskList\Models\User\UserId;
use App\Domain\TaskList\Repository\UsersRepositoryInterface;
use ArrayIterator;

class UsersRepository implements UsersRepositoryInterface
{
    /**
     * @var UserMutator
     */
    private $mutator;

    /**
     * UsersRepository constructor.
     */
    public function __construct()
    {
        $this->mutator = new UserMutator();
    }

    public function getUser(UserId $userId)
    {
        $entity = User::findOrFail((string)$userId);
        return $this->mutator->createDomain($entity);
    }

    public function saveUser(Domainuser $user)
    {
        $entity = $this->mutator->createEntity($user);
        $entity->save();
    }

    public function deleteUser(UserId $userId)
    {
        $entity = User::findOrFail((string)$userId);
        $entity->delete();
    }

    public function updateUser(Domainuser $user)
    {
        $entity = User::findOrFail((string)$user->getId());
        $this->mutator->updateEntity($entity,$user);
        $entity->save();
    }

    public function getAllUsers()
    {
        $users = User::all();
        $list = new ArrayIterator();
        foreach ($users as $user)
            $list->append($this->mutator->createDomain($user));
        return $list;
    }
}
