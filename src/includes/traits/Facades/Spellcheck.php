<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Spellcheck
{
    /**
     * @since 151214 Adding functions.
     */
    public static function isSpelledRight(...$args)
    {
        return $GLOBALS[static::class]->Utils->SpellCheck->__invoke(...$args);
    }
}
