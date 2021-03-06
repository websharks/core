<?php
/**
 * HTML entity utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
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
 * HTML entity utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlEntities extends Classes\Core\Base\Core
{
    /**
     * Escape HTML markup deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed    $value         Any input value.
     * @param bool     $double_encode Encode existing entities? Defaults to `FALSE`.
     * @param int|null $flags         Optional. Defaults to `ENT_HTML5 | ENT_QUOTES | ENT_SUBSTITUTE`.
     *
     * @return string|array|object Value after having been escaped deeply.
     */
    public function encode($value, bool $double_encode = false, int $flags = null)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->encode($_value, $double_encode, $flags);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        if (!isset($flags)) {
            $flags = ENT_HTML5 | ENT_QUOTES | ENT_SUBSTITUTE;
        }
        return htmlspecialchars($string, $flags, 'UTF-8', $double_encode);
    }

    /**
     * Unescape HTML markup deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     * @param int   $flags Optional. Defaults to `ENT_HTML5 | ENT_QUOTES`.
     *
     * @return string|array|object Value after having been unescaped deeply.
     */
    public function decode($value, int $flags = null)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->decode($_value, $flags);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        if (!isset($flags)) {
            $flags = ENT_HTML5 | ENT_QUOTES;
        }
        return html_entity_decode($string, $flags, 'UTF-8');
    }
}
