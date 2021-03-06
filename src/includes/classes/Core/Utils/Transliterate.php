<?php
/**
 * Transliteration utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

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
 * Transliteration utilities.
 *
 * @since 150424 Multibyte support.
 */
class Transliterate extends Classes\Core\Base\Core
{
    /**
     * Convert to ASCII.
     *
     * @since 150424 Multibyte support.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Output value.
     */
    public function toAscii($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->toAscii($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        return (string) transliterator_transliterate('Any-Latin; Latin-ASCII', $string);
    }
}
