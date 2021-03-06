<?php
/**
 * Crypto.
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
 * Crypto.
 *
 * @since 170309.60830
 */
trait Crypto
{
    /**
     * @since 160701 Unique ID.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UniqueId::__invoke()
     */
    public static function uniqueId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UniqueId->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RandomKey::__invoke()
     */
    public static function randomKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RandomKey->__invoke(...$args);
    }

    /**
     * @since 17xxxx Hash equals utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\HashEquals::__invoke()
     */
    public static function hashEquals(...$args)
    {
        return $GLOBALS[static::class]->Utils->©HashEquals->__invoke(...$args);
    }
}
