<?php


namespace App\Domain\User;


use App\Domain\Base\UuidType;

class UserId extends UuidType
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'UserID';
    }
}
