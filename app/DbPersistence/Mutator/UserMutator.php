<?php
namespace App\DbPersistence\Mutator;

use App\DbPersistence\Models\User as Entity;
use App\Domain\TaskList\Models\User\User as Domain;
use App\Domain\TaskList\Models\User\UserId;
use App\Domain\TaskList\Models\User\UserName;

class UserMutator implements BaseMutator
{
    /**
     * @param Domain $domain
     * @return Entity
     */
    public function createEntity(Domain $domain)
    {
        $entity = new Entity();
        $entity->id = $domain->getId()->getValue();
        $entity->name = $domain->getName()->getName();
        $entity->surname = $domain->getName()->getSurname();
        return $entity;
    }

    /**
     * @param Entity $entity
     * @param Domain $domain
     */
    public function updateEntity(Entity $entity,Domain $domain)
    {
        $entity->name = $domain->getName()->getName();
        $entity->surname = $domain->getName()->getSurname();
    }

    public function createDomain(Entity $entity)
    {
        return new Domain(
            UserId::fromString($entity->id),
            new UserName($entity->name,$entity->surname)
        );
    }

}
