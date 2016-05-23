<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Multibyte `lcfirst()`.
 *
 * @since 150424 Enhancing multibyte support.
 */
class MbLcFirst extends Classes\Core\Base\Core
{
    /**
     * Multibyte `lcfirst()`.
     *
     * @since 150424 Enhancing multibyte support.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Output value.
     */
    public function __invoke($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $first     = mb_substr($string, 0, 1);
        $remaining = mb_substr($string, 1);

        return mb_strtolower($first).$remaining;
    }
}
