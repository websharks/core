<?php
/**
 * Files.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
//
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
//
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Files.
 *
 * @since 151214
 */
trait Files
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileExt::__invoke()
     */
    public static function fileExt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileExt->__invoke(...$args);
    }

    /**
     * @since 17xxxx File ext utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileExt::set()
     */
    public static function setFileExt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileExt->set(...$args);
    }

    /**
     * @since 17xxxx File ext utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileExt::change()
     */
    public static function changeFileExt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileExt->change(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileSize::abbr()
     */
    public static function fileSizeAbbr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileSize->abbr(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileSize::bytesAbbr()
     */
    public static function bytesToAbbr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileSize->bytesAbbr(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileSize::abbrBytes()
     */
    public static function abbrToBytes(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileSize->abbrBytes(...$args);
    }

    /**
     * @since 170824.30708 Remote file size.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileSize::remoteBytes()
     */
    public static function remoteFileSize(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileSize->remoteBytes(...$args);
    }

    /**
     * @since 160926 Upload utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileUpload::errorReason()
     */
    public static function fileUploadErrorReason(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileUpload->errorReason(...$args);
    }
}
