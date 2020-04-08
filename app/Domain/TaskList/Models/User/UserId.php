<?php


namespace App\Domain\TaskList\Models\User;


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
