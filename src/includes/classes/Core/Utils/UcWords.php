<?php
/**
 * Multibyte `ucwords()`.
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
 * Multibyte `ucwords()`.
 *
 * @since 150424 Enhancing multibyte support.
 */
class UcWords extends Classes\Core\Base\Core
{
    /**
     * Multibyte `ucwords()`.
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
        return mb_convert_case($string, MB_CASE_TITLE);
    }
}
