<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

trait Php
{
    /**
     * @since 160505 PHP eval utils.
     */
    public static function phpEval(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PhpEval->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function canCallFunc(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PhpHas->callableFunc(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function memoryLimit(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memory->limit(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function stripPhpTags(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PhpStrip->tags(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uploadSizeLimit(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UploadSize->limit(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function maxExecTime(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ExecTime->max(...$args);
    }
}
