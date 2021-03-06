<?php
/**
 * HTML anchor rel="" utilities.
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
 * HTML anchor rel="" utilities.
 *
 * @since 160115 Initial release.
 */
class HtmlAnchorRels extends Classes\Core\Base\Core
{
    /**
     * Adds new `rel=""` attributes.
     *
     * @since 160115 `rel=""` attributes.
     *
     * @param mixed $value Any input value.
     * @param array `rel=""` attribute values.
     *
     * @return string|array|object HTML markup.
     */
    public function __invoke($value, array $rels)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $rels);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $Tokenizer = $this->c::tokenize($string, ['shortcodes', 'pre', 'code', 'samp', 'md-fences', 'md-links']);
        $string    = &$Tokenizer->getString(); // Now get string by reference.

        $string = preg_replace_callback('/\<a\s[^>]*\>/ui', function ($m) use ($rels) {
            if (preg_match('/\srel\s*\=\s*([\'"])(?<existing_rels>.*?)\\1/ui', $m[0], $_m)) {
                $existing_rels = preg_split('/\s+/u', mb_strtolower($_m['existing_rels']), -1, PREG_SPLIT_NO_EMPTY);
                $anchor = preg_replace('/\srel\s*\=\s*([\'"]).*?\\1/ui', '', $m[0]);
            } else {
                $existing_rels = []; // None yet.
                $anchor = $m[0]; // As-is in this case.
            }
            $new_rels = $existing_rels ? array_unique(array_merge($existing_rels, $rels)) : $rels;
            return $anchor = str_replace('>', ' rel="'.$this->c::escAttr(implode(' ', $new_rels)).'">', $anchor);
        }, $string); // Adds new `rel=""` attributes.

        return $string = $Tokenizer->restoreGetString();
    }
}
