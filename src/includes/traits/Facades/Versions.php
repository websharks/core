<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

trait Versions
{
    /**
     * @since 151214 Adding functions.
     */
    public static function isVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Version->isValid(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isDevVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Version->isValidDev(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isStableVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Version->isValidStable(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isWsVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WsVersion->isValid(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isWsDevVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WsVersion->isValidDev(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isWsStableVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WsVersion->isValidStable(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function wsVersionDate(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WsVersion->date(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function wsVersionTime(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WsVersion->time(...$args);
    }
}
