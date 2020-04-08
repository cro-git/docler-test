<?php


namespace App\Domain;

use App\Domain\Task\TaskStatus;
use App\Domain\TaskList\TaskListId;
use App\Domain\TaskList\TaskListName;
use App\Domain\User\UserId;
use ArrayIterator;

class TaskList
{
    /** @var TaskListId */
    private $id;

    /** @var UserId */
    private $userId;

    /** @var Task[] */
    private $tasks;

    /**
     * TaskList constructor.
     * @param TaskListId $id
     * @param UserId $userId
     * @param TaskListName $name
     * @param ArrayIterator $tasks
     */
    public function __construct(TaskListId $id, UserId $userId,TaskListName $name,ArrayIterator $tasks)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->tasks = $tasks;
    }

    /**
     * Add a new task to the Task List
     * @param Task $task
     * @return $this
     */
    public function addTask(Task $task)
    {
        $this->tasks->append($task);
        return $this;
    }

    /**
     * @return TaskListId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return UserId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return TaskListName
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Check if there is task in the Task List
     * @return bool
     */
    public function hasTask()
    {
        return $this->tasks->count() > 0;
    }

    /**
     * Check if there is at least a task to do
     * @return bool
     */
    public function hasTaskToDo()
    {
        foreach ($this->tasks as $task)
            if (!$task->isDone())
                return true;
        return false;
    }

    /**
     * Return a list of task due today
     * You can get a filtered task list using the parameter status, if not set it will return all the task due today
     *
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

    public function equals(TaskList $list)
    {
        return $this->getId()->equals($list->getId());
    }

}
