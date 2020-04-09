<?php
namespace  App\Domain\TaskList\Serializer;

use App\Domain\TaskList\Models\User\User;
use Iterator;


class UserSerializer extends BaseSerializer
{
    public static function json(User $user)
    {
        return [
            'id' => $user->getId()->getValue(),
            'name' => $user->getName()->getName(),
            'surname' => $user->getName()->getSurname()
        ];
    }
}
