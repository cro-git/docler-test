<?php


namespace App\Domain;


use App\Domain\Event\NewUserHasBeenCreated;
use App\Domain\Event\UserHasBeenDeleted;
use App\Domain\Event\UserHasBeenUpdated;
use App\Domain\User\UserId;
use App\Domain\User\UserName;

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
        // Todo: check if the userId is valid
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
        event(new NewUserHasBeenCreated($user));
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
