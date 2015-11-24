<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Array dimension utilities.
 *
 * @since 150424 Initial release.
 */
class ArrayDimensions extends Classes\AbsBase
{
    /**
     * Forces an array to a single dimension.
     *
     * @since 15xxxx Adding array utils.
     *
     * @param array $array Input array.
     *
     * @return array Output array, with only ONE dimension.
     */
    public function one(array $array): array
    {
        foreach ($array as $_key => $_value) {
            if (is_array($_value) || is_object($_value)) {
                unset($array[$_key]);
            }
        }
        unset($_key, $_value); // Housekeeping.

        return $array;
    }
}
