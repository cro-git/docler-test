<?php


namespace App\Domain\Task;


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
