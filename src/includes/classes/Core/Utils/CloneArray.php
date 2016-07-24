<?php
/**
 * Clone an array deeply.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
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
 * Clone an array deeply.
 *
 * @since 160511 Array cloning.
 */
class CloneArray extends Classes\Core\Base\Core
{
    /**
     * Clone an array deeply.
     *
     * @since 160511 Array cloning.
     *
     * @param array $array Input array.
     *
     * @return array Output array.
     */
    public function __invoke(array $array)
    {
        $clone = []; // Initialize.

        foreach ($array as $_key => $_value) {
            if (is_array($_value)) {
                $clone[$_key] = $this->__invoke($_value);
            } elseif (is_object($_value)) {
                $clone[$_key] = clone $_value;
            } else {
                $clone[$_key] = $_value;
            }
        } // unset($_key, $_value); // Housekeeping.

        return $clone; // Copy w/o references.
    }
}
