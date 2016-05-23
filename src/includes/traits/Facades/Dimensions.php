<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

trait Dimensions
{
    /**
     * @since 151214 Adding functions.
     */
    public static function oneDimension(...$args)
    {
        return $GLOBALS[static::class]->Utils->©OneDimension->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function removeEmptys(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RemoveEmptys->__invoke(...$args);
    }

    /**
     * @since 160428 Remove 0 bytes.
     */
    public static function remove0Bytes(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Remove0Bytes->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function removeNulls(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RemoveNulls->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function sortByKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sort->byKey(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function dotKeys(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DotKeys->__invoke(...$args);
    }
}
