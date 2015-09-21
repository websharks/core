<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * HTML strip utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlStrip extends AbsBase
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
     * Strips HTML attributes deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     * @param array $args  Any additional behavioral args.
     *
     * @return string String w/ HTML attributes stripped.
     */
    public function attributes($value, array $args = array())
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->attributes($_value, $args);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string       = (string) $value;
        $default_args = array(
            'allowed_attributes' => [],
        );
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $allowed_attributes = // Force lowercase.
            array_map('strtolower', (array) $args['allowed_attributes']);

        $regex_tags  = '/(?P<open>\<[\w\-]+)(?P<attrs>[^>]+)(?P<close>\>)/i';
        $regex_attrs = '/\s+(?P<attr>[\w\-]+)(?:\s*\=\s*(["\']).*?\\2|\s*\=[^\s]*)?/is';

        return preg_replace_callback($regex_tags, function ($m) use ($allowed_attributes, $regex_attrs) {
            return $m['open'].preg_replace_callback($regex_attrs, function ($m) use ($allowed_attributes) {
                return in_array(strtolower($m['attr']), $allowed_attributes, true) ? $m[0] : '';
            }, $m['attrs']).$m['close']; // With modified attributes.

        }, $string); // Removes attributes; leaving only those allowed explicitly.
    }
}
