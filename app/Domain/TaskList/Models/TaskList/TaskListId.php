<?php


namespace App\Domain\TaskList\Models\TaskList;


use App\Domain\Base\UuidType;

class TaskListId extends UuidType
{

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'TaskListId';
    }
}
