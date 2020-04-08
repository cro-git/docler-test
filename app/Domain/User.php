<?php


namespace App\Domain;


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
        return $this;
    }

    public static function create(UserName $name)
    {
        return new User(
            UserId::generate(),
            $name
        );
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
