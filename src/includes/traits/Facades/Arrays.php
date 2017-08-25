<?php
/**
 * Arrays.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Arrays.
 *
 * @since 160102
 */
trait Arrays
{
    /**
     * @since 160511 Array cloning.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\ArrayClone::__invoke()
     */
    public static function cloneArray(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ArrayClone->__invoke(...$args);
    }

    /**
     * @since 17xxxx Swap recursive.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\ArraySwapRecursive::__invoke()
     */
    public static function arraySwapRecursive(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ArraySwapRecursive->__invoke(...$args);
    }

    /**
     * @since 160102 App.
     * @see Classes\Core\Utils\ArrayUnshiftAssoc::__invoke()
     *
     * @param array      $array An input array; by reference.
     * @param string|int $key   New array key; string or integer.
     * @param mixed      $value New array value.
     */
    public static function arrayUnshiftAssoc(&$array, $key, $value)
    {
        return $GLOBALS[static::class]->Utils->©ArrayUnshiftAssoc->__invoke($array, $key, $value);
    }
}
