<?php


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
