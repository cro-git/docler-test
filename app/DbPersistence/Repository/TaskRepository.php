<?php

namespace App\DbPersistence\Repository;

use App\DbPersistence\Models\Task;
use App\DbPersistence\Mutator\TaskListMutator;
use App\DbPersistence\Mutator\TaskMutator;
use App\Domain\TaskList\Models\Task\Task as DomainTask;
use App\Domain\TaskList\Models\Task\TaskId;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use App\Domain\TaskList\Repository\TaskRepositoryInterface;
use ArrayIterator;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @var TaskListMutator
     */
    private $mutator;

    /**
     * UsersRepository constructor.
     */
    public function __construct()
    {
        $this->mutator = new TaskMutator();
    }

    /**
     * @inheritDoc
     */
    public function getTask(TaskId $taskId)
    {
        $entity = Task::findOrFail((string)TaskId);
        return $this->mutator->createDomain($entity);
    }

    public function saveTask(DomainTask $task)
    {
        $entity = $this->mutator->createEntity($task);
        $entity->save();
    }

    public function deleteTask(TaskId $taskId)
    {
        $entity = Task::findOrFail((string)$taskId);
        $entity->delete();
    }

    public function updateTask(DomainTask $task)
    {
        $entity = Task::findOrFail((string)$task->getId());
        $this->mutator->updateEntity($entity,$task);
        $entity->save();
    }

    /**
     * @inheritDoc
     */
    public function getTaskOfList(TaskListId $taskListId)
    {
        $tasks = Task::where('task_list_id',(string)$taskListId)->get();
        $list = new ArrayIterator();
        foreach ($tasks as $task)
            $list->append($this->mutator->createDomain($task));
        return $list;
    }

}
