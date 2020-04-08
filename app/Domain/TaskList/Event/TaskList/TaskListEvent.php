<?php


namespace App\Domain\TaskList\Event\TaskList;


use App\Domain\TaskList\Models\TaskList\TaskList;
use Illuminate\Queue\SerializesModels;

abstract class TaskListEvent
{
    use SerializesModels;

    /**
     * @var TaskList
     */
    public $taskList;

    public function __construct(TaskList $taskList)
    {
        $this->taskList = $taskList;
    }
}
