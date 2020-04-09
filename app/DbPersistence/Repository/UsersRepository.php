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
        $entity = User::findOrFail($userId->getValue());
        return $this->mutator->createDomain($entity);
    }

    public function saveUser(Domainuser $user)
    {
        $entity = $this->mutator->createEntity($user);
        $entity->save();
    }

    public function deleteUser(UserId $userId)
    {
        $entity = User::where('id',$userId->getValue())->first();
        $entity->delete();
    }

    public function updateUser(Domainuser $user)
    {
        $entity = User::where('id',$user->getId()->getValue())->first();
        $this->mutator->updateEntity($entity,$user);
        $entity->save();
    }

    public function getAllUsers()
    {
        $users = User::all();
        $domainUsers = new ArrayIterator();
        foreach ($users as $user)
            $domainUsers->append($this->mutator->createDomain($user));
        return $domainUsers;
    }
}
