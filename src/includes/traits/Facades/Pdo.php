<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait Pdo
{
    /**
     * @since 160422 SQL utils.
     */
    public static function pdoQuote(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Pdo->quote(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function pdoGet(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Pdo->get(...$args);
    }

    /**
     * @since 160422 SQL utils.
     */
    public static function currentPdo()
    {
        return $GLOBALS[static::class]->Utils->©Pdo->current;
    }
}
