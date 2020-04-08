<?php


namespace App\Domain\TaskList\Event\Task;


use App\Domain\TaskList\Models\Task\Task;
use Illuminate\Queue\SerializesModels;

abstract class TaskEvent
{
    use SerializesModels;

    /**
     * @var Task
     */
    public $task;

    public function __construct(Task $taskList)
    {
        $this->task = $taskList;
    }
}
