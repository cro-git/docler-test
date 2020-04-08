<?php
namespace Tests\Domain\AbastractTestClasses;

class AbstractUuid extends \App\Domain\Base\UuidType
{

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'testUuid';
    }
}
