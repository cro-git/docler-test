<?php


namespace App\Domain\TaskList\Models\Task;


use App\Domain\Base\UuidType;

class TaskId extends UuidType
{

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'TaskId';
    }
}
