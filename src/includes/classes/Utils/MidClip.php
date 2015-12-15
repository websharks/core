<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * MidClip utilities.
 *
 * @since 150424 Initial release.
 */
class MidClip extends Classes\AbsBase
{
    /**
     * Mid-clips string(s) to X chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value      Any input value.
     * @param int   $max_length Defaults to `80`.
     *
     * @return string|array|object Mid-clipped value.
     */
    public function __invoke($value, int $max_length = 80)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $max_length);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        $max_length      = max(6, $max_length);
        $half_max_length = floor($max_length / 2);
        $string          = c\html_to_text($string, ['br2nl' => false]);
        $full_string     = $string; // Remember full string.

        if (mb_strlen($string) <= $max_length) {
            return $string; // Nothing to do.
        }
        $first_clip = $half_max_length - 5;
        $string     = $first_clip >= 1 ? mb_substr($full_string, 0, $first_clip).'[...]' : '[...]';

        $second_clip = mb_strlen($full_string) - ($max_length - mb_strlen($string));
        $string .= $second_clip >= 0 && $second_clip >= $first_clip ? mb_substr($full_string, $second_clip) : '';

        return $string;
    }
}