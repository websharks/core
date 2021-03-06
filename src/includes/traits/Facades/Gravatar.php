<?php
/**
 * Gravatar.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
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
 * Gravatar.
 *
 * @since 160114
 */
trait Gravatar
{
    /**
     * @since 160114 Gravatars.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Gravatar::url()
     */
    public static function gravatarUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Gravatar->url(...$args);
    }
}
