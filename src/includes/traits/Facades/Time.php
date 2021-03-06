<?php
/**
 * Time.
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
 * Time.
 *
 * @since 170124.74961
 */
trait Time
{
    /**
     * @since 170124.74961 Human time diff.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Time::humanDiff()
     */
    public static function humanTimeDiff(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Time->humanDiff(...$args);
    }
}
