<?php
namespace  App\Domain\TaskList\Serializer;

use App\Domain\TaskList\Models\User\User;


class UserSerializer extends BaseSerializer
{
    public function json(User $user)
    {
        return [
            'id' => (string)$user->getId(),
            'name' => $user->getName()->getName(),
            'surname' => $user->getName()->getSurname()
        ];
    }
}
