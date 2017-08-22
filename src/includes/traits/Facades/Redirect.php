<?php
/**
 * Redirect.
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
 * Redirect.
 *
 * @since 17xxxx Redirect.
 */
trait Redirect
{
    /**
     * @since 17xxxx Redirect.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Redirect::__invoke()
     */
    public static function redirect(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Redirect->__invoke(...$args);
    }
}