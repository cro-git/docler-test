<?php
namespace  App\Domain\TaskList\Serializer;

use Iterator;

/**
 * Interface BaseSerializer
 * @package App\Domain\TaskList\Serializer
 * @method static json($domain)
 */
abstract class BaseSerializer
{
    public static function jsonList(Iterator $items)
    {
        $ret = [];
        foreach ($items as $item)
            $ret[] = static::json($item);
        return $ret;
    }
}
