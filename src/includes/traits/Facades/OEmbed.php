<?php
/**
 * OEmbed.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * OEmbed.
 *
 * @since 151214
 */
trait OEmbed
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\OEmbed::__invoke()
     */
    public static function oEmbed(...$args)
    {
        return $GLOBALS[static::class]->Utils->©OEmbed->__invoke(...$args);
    }
}
