<?php


namespace App\Domain\TaskList\Models\TaskList;

use App\Domain\TaskList\Event\TaskList\TaskListHasBeenCreated;
use App\Domain\TaskList\Event\TaskList\TaskListHasBeenUpdated;
use App\Domain\TaskList\Models\Task\Task;
use App\Domain\TaskList\Models\Task\TaskStatus;
use App\Domain\TaskList\Models\User\UserId;
use App\Domain\TaskList\Repository\TaskListRepositoryInterface;
use App\Domain\TaskList\Repository\TaskRepositoryInterface;
use ArrayIterator;

class TaskList
{
    /** @var TaskListId */
    private $id;

    /** @var UserId */
    private $userId;

    /** @var TaskListName  */
    private $name;

    /**
     * TaskList constructor.
     * @param TaskListId $id
     * @param UserId $userId
     * @param TaskListName $name
     */
    public function __construct(TaskListId $id, UserId $userId,TaskListName $name)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
    }

    /**
     * @param UserId $userId
     * @param TaskListName $name
     * @return TaskList
     */
    public static function create(UserId $userId,TaskListName $name)
    {
        $taskList = new TaskList(TaskListId::generate(), $userId, $name);
        event(new TaskListHasBeenCreated($taskList));
        return $taskList;
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

    public function getTasks()
    {
        /** @var TaskRepositoryInterface $repository */
        $repository = resolve(TaskRepositoryInterface::class);
        return $repository->getTaskOfList($this->id);
    }

    /**
     * Check if there is task in the Task List
     * @return bool
     */
    public function hasTask()
    {
        return $this->getTasks()->count() > 0;
    }

    /**
     * Check if there is at least a task to do
     * @return bool
     */
    public function hasTaskToDo()
    {
        foreach ($this->getTasks() as $task)
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
        foreach ($this->getTasks() as $task)
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
