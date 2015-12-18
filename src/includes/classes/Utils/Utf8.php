<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * UTF-8 Utils.
 *
 * @since 15xxxx Enhancing multibyte support.
 */
class Utf8 extends Classes\AppBase
{
    /**
     * Marker.
     *
     * @since 15xxxx
     *
     * @type string
     */
    const BOM = "\xEF\xBB\xBF";

    /**
     * Is valid UTF-8?
     *
     * @since 15xxxx Enhancing multibyte support.
     *
     * @param string $string Input string to test.
     *
     * @return bool True if is valid UTF-8.
     */
    public function isValid(string $string): bool
    {
        if (!isset($string[0])) {
            return true; // Nothing to do.
        }
        return (bool) preg_match('/^./us', $string);
    }
}
