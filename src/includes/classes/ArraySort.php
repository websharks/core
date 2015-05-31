<?php
namespace WebSharks\Core\Classes;

/**
 * Array sort utilities.
 *
 * @since 150424 Initial release.
 */
class ArraySort extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Sorts an array deeply by its keys.
     *
     * @param array $array Input array to sort.
     * @param int   $flags Optional; defaults to `SORT_REGULAR`.
     *
     * @return array Array sorted deeply by its keys.
     */
    public function byKey(array $array, $flags = SORT_REGULAR)
    {
        ksort($array, $flags);

        foreach ($array as $_key => &$_value) {
            if (is_array($_value)) {
                $_value = $this->byKey($_value, $flags);
            }
        }
        unset($_key, $_value); // Housekeeping.

        return $array;
    }
}