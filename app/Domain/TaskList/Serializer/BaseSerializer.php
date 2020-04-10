<?php
namespace  App\Domain\TaskList\Serializer;

use Iterator;

/**
 * Interface BaseSerializer
 * @package App\Domain\TaskList\Serializer
 * @method json($item)
 */
abstract class BaseSerializer
{
    public function jsonList(Iterator $items)
    {
        $ret = [];
        foreach ($items as $item)
            $ret[] = $this->json($item);
        return $ret;
    }
}
