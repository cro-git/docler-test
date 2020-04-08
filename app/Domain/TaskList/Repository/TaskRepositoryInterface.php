<?php
namespace App\Domain\TaskList\Repository;


use App\Domain\TaskList\Models\Task\Task;
use App\Domain\TaskList\Models\Task\TaskId;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use ArrayIterator;

interface TaskRepositoryInterface
{
    /**
     * @param TaskId $taskId
     * @return Task
     */
    public function getTask(TaskId $taskId);

    public function saveTask(Task $task);

    public function deleteTask(TaskId $taskId);

    public function updateTask(Task $task);

    /**
     * @param TaskListId $taskListId
     * @return ArrayIterator|Task[]
     */
    public function getTaskOfList(TaskListId $taskListId);
}
