<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

trait Arrays
{
    /**
     * @since 160102 App.
     */
    public static function arrayUnshiftAssoc(&$array, $key, $value)
    {
        return $GLOBALS[static::class]->Utils->©UnshiftAssoc->__invoke($array, $key, $value);
    }

    /**
     * @since 160511 Array cloning.
     */
    public static function cloneArray(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CloneArray->__invoke(...$args);
    }
}
