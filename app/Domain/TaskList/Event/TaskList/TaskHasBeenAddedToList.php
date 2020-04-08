<?php
namespace App\Domain\TaskList\Event\TaskList;


use App\Domain\TaskList\Models\Task\TaskId;
use App\Domain\TaskList\Models\TaskList\TaskList;

class TaskHasBeenAddedToList extends TaskListEvent
{
    public function __construct(TaskList $taskList,TaskId $task)
    {
        $this->taskList = $taskList;
    }
}
