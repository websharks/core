<?php
/**
 * GitHub.
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
 * GitHub.
 *
 * @since 170124.74961
 */
trait GitHub
{
    /**
     * @since 170124.74961 GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::getRaw()
     */
    public static function gitHubGetRaw(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->getRaw(...$args);
    }

    /**
     * @since 170124.74961 GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::getJson()
     */
    public static function gitHubGetJson(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->getJson(...$args);
    }

    /**
     * @since 170124.74961 GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::submitJson()
     */
    public static function gitHubSubmitJson(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->submitJson(...$args);
    }

    /**
     * @since 170124.74961 GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::postJson()
     */
    public static function gitHubPostJson(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->postJson(...$args);
    }

    /**
     * @since 170124.74961 GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::patchJson()
     */
    public static function gitHubPatchJson(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->patchJson(...$args);
    }

    /**
     * @since 170124.74961 GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::issueRefs()
     */
    public static function gitHubIssueRefs(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->issueRefs(...$args);
    }

    /**
     * @since 170124.74961 GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::mdIssueRefs()
     */
    public static function mdGitHubIssueRefs(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->mdIssueRefs(...$args);
    }
}
