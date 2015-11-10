<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * ASCII utilities.
 *
 * @since 15xxxx Multibyte support.
 */
class Ascii extends AbsBase
{
    protected $Transliterator;

    /**
     * Class constructor.
     *
     * @since 15xxxx Multibyte support.
     */
    public function __construct()
    {
        parent::__construct();

        $this->Transliterator = Transliterator::create($rule);
    }

    /**
     * Convert to ASCII.
     *
     * @since 15xxxx Multibyte support.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Output value.
     */
    public function __invoke($value): string
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value);
            } // unset($_key, $_value);

            return $value;
        }
        $string = (string) $value;

        if (!isset($string[0])) {
            return $string;
        }
        return (string) $this->Transliterator->transliterate('Any-Latin; Latin-ASCII', $string);
    }
}