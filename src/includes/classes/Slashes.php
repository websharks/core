<?php
namespace WebSharks\Core\Classes;

/**
 * Slashes.
 *
 * @since 150424 Initial release.
 */
class Slashes extends AbsBase
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
     * Adds slashes deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Slashed value.
     */
    public function add($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->add($_value);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;

        return addslashes($string);
    }

    /**
     * Strips slashes deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Unslashed value.
     */
    public function strip($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->strip($_value);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;

        return stripslashes($string);
    }
}