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

trait App
{
    /**
     * @since 160102 App.
     */
    public static function app(...$args)
    {
        return $GLOBALS[static::class];
    }

    /**
     * @since 160227 App.
     * @deprecated 160711 Do not use.
     */
    public static function config(...$args)
    {
        return $GLOBALS[static::class]->Config;
    }

    /**
     * @since 160227 App.
     * @deprecated 160711 Do not use.
     */
    public static function version(...$args)
    {
        return $GLOBALS[static::class]::VERSION;
    }

    /**
     * @since 151214 Adding functions.
     * @deprecated 160711 Do not use.
     */
    public static function diGet(...$args)
    {
        return $GLOBALS[static::class]->Di->get(...$args);
    }
}
