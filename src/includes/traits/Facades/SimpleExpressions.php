<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

trait SimpleExpressions
{
    /**
     * @since 160708 Simple expression utils.
     */
    public static function simplePhpExpr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©SimpleExpression->toPhp(...$args);
    }
}