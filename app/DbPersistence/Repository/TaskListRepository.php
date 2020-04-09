<?php

namespace App\DbPersistence\Repository;

use App\DbPersistence\Models\TaskList;
use App\DbPersistence\Mutator\TaskListMutator;
use App\Domain\TaskList\Models\TaskList\TaskList as DomainTaskList;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use App\Domain\TaskList\Models\User\UserId;
use App\Domain\TaskList\Repository\TaskListRepositoryInterface;
use ArrayIterator;

class TaskListRepository implements TaskListRepositoryInterface
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
        $this->mutator = new TaskListMutator();
    }


    public function getTaskList(TaskListId $taskListId)
    {
        $entity = TaskList::findOrFail((string)$taskListId);
        return $this->mutator->createDomain($entity);
    }

    public function saveTaskList(DomainTaskList $taskList)
    {
        $entity = $this->mutator->createEntity($taskList);
        $entity->save();
    }

    public function deleteTaskList(TaskListId $taskListId)
    {
        $entity = TaskList::findOrFail((string)$taskListId);
        $entity->delete();
    }

    public function updateTaskList(DomainTaskList $taskList)
    {
        $entity = TaskList::findOrFail((string)$taskList->getId());
        $this->mutator->updateEntity($entity,$taskList);
        $entity->save();
    }

    public function getAllTaskListByUser(UserId $userId)
    {
        $taskLists = TaskList::where('user_id',(string)$userId);
        $list = new ArrayIterator();
        foreach ($taskLists as $taskList)
            $list->append($this->mutator->createDomain($taskList));
        return $list;
    }
}
