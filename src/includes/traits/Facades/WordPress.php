<?php
/**
 * WordPress.
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
 * WordPress.
 *
 * @since 160219
 */
trait WordPress
{
    /**
     * @since 160219 WP utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WordPress::is()
     */
    public static function isWordPress(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WordPress->is(...$args);
    }
}
