<?php
/**
 * PHP strip utilities.
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
 * PHP strip utilities.
 *
 * @since 150424 Initial release.
 */
class PhpStrip extends Classes\Core\Base\Core
{
    /**
     * Strips PHP tags.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Output value.
     */
    public function tags($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->tags($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        $regex = // Search for PHP tags.
            '/'.// Open pattern delimiter.
            '(?:'.// Any of these.

                '\<\?php.*?\?\>|\<\?\=.*?\?\>|\<\?.*?\?\>|\<%.*?%\>'.
                '|\<script\s+[^>]*?language\s*\=\s*(["\'])?php\\1[^>]*\>.*?\<\s*\/\s*script\s*\>'.

            ')'.// Close 'any of these'.
            '/uis'; // End pattern.
        return preg_replace($regex, '', $string);
    }
}
