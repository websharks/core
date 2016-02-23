<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Associative `unshift()`.
 *
 * @since 150424 Initial release.
 */
class UnshiftAssoc extends Classes\Core
{
    /**
     * Prepend a key/value pair onto an array.
     *
     * @since 141111 First documented version.
     *
     * @param array $array An input array; by reference.
     * @param string|int New array key; string or integer.
     * @param mixed $value New array value.
     *
     * @return int Like {@link \array_unshift()}, returns the new number of elements.
     */
    public function __invoke(array &$array, $key, $value)
    {
        if (!is_int($key) && !is_string($key)) {
            throw new Exception('Invalid `$key`.');
        }
        unset($array[$key]); // Unset first.

        $array       = array_reverse($array, true);
        $array[$key] = $value; // Add to the end here.
        $array       = array_reverse($array, true);

        return count($array);
    }
}