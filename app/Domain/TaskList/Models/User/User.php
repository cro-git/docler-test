<?php


namespace App\Domain\TaskList\Models\User;


use App\Domain\TaskList\Event\User\UserHasBeenCreated;
use App\Domain\TaskList\Event\User\UserHasBeenDeleted;
use App\Domain\TaskList\Event\User\UserHasBeenUpdated;
use App\Domain\TaskList\Repository\UsersRepositoryInterface;


class User
{
    /** @var UserId */
    private $id;

    /** @var UserName */
    private $name;

    /**
     * User constructor.
     * @param UserId $id
     * @param UserName $name
     */
    public function __construct(UserId $id, UserName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @param UserName $name
     * @return $this
     */
    public function changeName(UserName $name)
    {
        $this->name = $name;
        event(new UserHasBeenUpdated($this));
        return $this;
    }

    public static function create(UserName $name)
    {
        $user = new User(UserId::generate(), $name);
        event(new UserHasBeenCreated($user));
        return $user;
    }

    public function delete()
    {
        event(new UserHasBeenDeleted($this));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Two users are the same if their ID is the same even if they have different name,
     * we are not comparing object, we just want to know if they are the same domain object
     *
     * @param User $user
     * @return bool
     */
    public function equals(self $user)
    {
        return $this->id->equals($user->getId());
    }
}
