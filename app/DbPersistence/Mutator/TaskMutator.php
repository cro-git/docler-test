<?php


namespace App\DbPersistence\Mutator;


use App\DbPersistence\Models\Task as Entity;
use App\Domain\TaskList\Models\Task\Task as Domain;
use App\Domain\TaskList\Models\Task\TaskDescription;
use App\Domain\TaskList\Models\Task\TaskDueDate;
use App\Domain\TaskList\Models\Task\TaskId;
use App\Domain\TaskList\Models\Task\TaskStatus;
use App\Domain\TaskList\Models\TaskList\TaskListId;


class TaskMutator implements BaseMutator
{
    /**
     * @param Domain $domain
     * @return Entity
     */
    public function createEntity(Domain $domain)
    {
        $entity = new Entity();
        $entity->id = $domain->getId();
        $entity->description = $domain->getDescription()->getValue();
        $entity->due_date = $domain->getDueDate()->getValue();
        $entity->status = $domain->getStatus()->getValue();
        $entity->task_list_id = $domain->getTaskListId()->getValue();
        return $entity;
    }

    /**
     * @param Entity $entity
     * @param Domain $domain
     */
    public function updateEntity(Entity $entity,Domain $domain)
    {
        $entity->description = $domain->getDescription()->getValue();
        $entity->due_date = $domain->getDueDate()->getValue();
        $entity->status = $domain->getStatus()->getValue();
    }

    public function createDomain(Entity $entity)
    {
        return new Domain(
            TaskId::fromString($entity->id),
            new TaskDescription($entity->description),
            new TaskStatus($entity->status),
            new TaskDueDate($entity->due_date),
            new TaskListId($entity->task_list_id)
        );
    }
}
