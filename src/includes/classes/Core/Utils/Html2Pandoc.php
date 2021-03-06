<?php
/**
 * Html2Pandoc utilities.
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
#
use Pandoc\Pandoc;

/**
 * Html2Pandoc utilities.
 *
 * @since 150424 Initial release.
 */
class Html2Pandoc extends Classes\Core\Base\Core
{
    /**
     * Converts HTML into structured text.
     *
     * @param mixed  $value Any input value.
     * @param string $to    The Pandoc-compatible format.
     *
     * @return string|array|object Converted value(s).
     */
    public function __invoke($value, string $to)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $to);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        } elseif (!$to) { // Convert to nothing?
            return $string; // Nothing to do.
        }
        try { // Fail gracefully.
            $options = [
                'from'          => 'html',
                'to'            => $to,
                'parse-raw'     => null,
                'atx-headers'   => null,
                'no-wrap'       => null,
                'preserve-tabs' => null,
            ];
            $Pandoc = new Pandoc();
            return $Pandoc->runWith($string, $options);
        } catch (\Throwable $Throwable) {
            return ''; // Failure.
        }
    }
}
