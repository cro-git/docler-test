<?php
namespace App\Domain\TaskList\Repository;


use App\Domain\TaskList\Models\TaskList\TaskList;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use App\Domain\TaskList\Models\User\UserId;

interface TaskListRepositoryInterface
{
    public function getTaskList(TaskListId $taskListId);

    public function saveTaskList(TaskList $taskList);

    public function deleteTaskList(TaskListId $taskListId);

    public function updateTaskList(TaskList $taskList);

    public function getTaskListsByUser(UserId $userId);
}
