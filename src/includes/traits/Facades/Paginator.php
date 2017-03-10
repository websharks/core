<?php
/**
 * Paginator.
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
 * Paginator.
 *
 * @since 161006
 */
trait Paginator
{
    /**
     * @since 161006 Paginator.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Paginator::get()
     */
    public static function getPaginator(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Paginator->get(...$args);
    }
}
