<?php


namespace App\Domain;


use App\Domain\Task\TaskId;
use App\Domain\Task\TaskStatus;
use App\Domain\TaskList\TaskListName;
use App\Domain\User\UserId;
use ArrayIterator;

class TaskList
{
    /** @var TaskId */
    private $id;

    /** @var UserId */
    private $userId;

    /** @var Task[] */
    private $tasks;

    /**
     * TaskList constructor.
     * @param TaskId $id
     * @param UserId $userId
     * @param TaskListName $name
     * @param ArrayIterator $tasks
     */
    public function __construct(TaskId $id, UserId $userId,TaskListName $name,ArrayIterator $tasks)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->tasks = $tasks;
    }

    public function addTask(Task $task)
    {
        $this->tasks->append($task);
    }

    /**
     * @param TaskStatus $status
     * @return ArrayIterator
     */
    public function getTodayTasks($status = null)
    {
        $tasks = new ArrayIterator();

        /** @var Task $task */
        foreach ($this->tasks as $task)
            if ($task->isDueToday()) {
                if ($status === null || $task->getStatus()->equals($status))
                    $tasks->append($task);
            }
        return $tasks;
    }

}
