<?php


namespace App\DbPersistence\Mutator;


use App\DbPersistence\Models\TaskList as Entity;
use App\Domain\TaskList\Models\TaskList\TaskList as Domain;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use App\Domain\TaskList\Models\TaskList\TaskListName;
use App\Domain\TaskList\Models\User\UserId;


class TaskListMutator implements BaseMutator
{
    /**
     * @param Domain $domain
     * @return Entity
     */
    public function createEntity(Domain $domain)
    {
        $entity = new Entity();
        $entity->id = $domain->getId()->getValue();
        $entity->name = $domain->getName()->getValue();
        $entity->user_id = $domain->getUserId()->getValue();
        return $entity;
    }

    /**
     * @param Entity $entity
     * @param Domain $domain
     */
    public function updateEntity(Entity $entity,Domain $domain)
    {
        $entity->name = $domain->getName()->getValue();
    }

    public function createDomain(Entity $entity)
    {
        return new Domain(
            TaskListId::fromString($entity->id),
            UserId::fromString($entity->user_id),
            new TaskListName($entity->name)
        );
    }
}
