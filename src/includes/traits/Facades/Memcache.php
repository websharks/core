<?php
/**
 * Memcache.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Memcache.
 *
 * @since 151214
 */
trait Memcache
{
    /**
     * @since 170215.53419 Memcache enabled?
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Memcache::enabled()
     */
    public static function memcacheEnabled(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->enabled(...$args);
    }

    /**
     * @since 170215.53419 Memcache info.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Memcache::info()
     */
    public static function memcacheInfo(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->info(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Memcache::set()
     */
    public static function memcacheSet(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->set(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Memcache::get()
     */
    public static function memcacheGet(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->get(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Memcache::touch()
     */
    public static function memcacheTouch(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->touch(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Memcache::clear()
     */
    public static function memcacheClear(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->clear(...$args);
    }
}
