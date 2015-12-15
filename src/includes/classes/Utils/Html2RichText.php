<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Html2RichText utilities.
 *
 * @since 150424 Initial release.
 */
class Html2RichText extends Classes\AbsBase implements Interfaces\HtmlConstants
{
    /**
     * Convert HTML to rich text; w/ allowed tags only.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     * @param array $args  Any additional behavioral args.
     *
     * @return string|array|object Rich text value(s).
     */
    public function __invoke($value, array $args = [])
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $args);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $default_args = [
            'br2nl' => true,

            'allowed_tags' => [
                'a',
                'strong', 'b',
                'i', 'em',
                'ul', 'ol', 'li',
                'code', 'pre',
                'q', 'blockquote',
            ],
            'allowed_attributes' => [
                'href',
            ],

            'strip_content_in_tags' => $this::HTML_INVISIBLE_TAGS,
            'inject_eol_after_tags' => $this::HTML_BLOCK_TAGS,
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $br2nl = (bool) $args['br2nl']; // Allow line breaks?

        $allowed_tags = (array) $args['allowed_tags'];
        if ($br2nl) {
            $allowed_tags[] = 'br'; // Allow `<br>` also.
        }
        $allowed_tags       = array_unique(array_map('mb_strtolower', $allowed_tags));
        $allowed_attributes = (array) $args['allowed_attributes'];

        $strip_content_in_tags            = (array) $args['strip_content_in_tags'];
        $strip_content_in_tags            = array_map('mb_strtolower', $strip_content_in_tags);
        $strip_content_in_tags            = array_diff($strip_content_in_tags, $allowed_tags);
        $strip_content_in_tags_regex_frag = implode('|', c\esc_regex($strip_content_in_tags));

        $inject_eol_after_tags            = (array) $args['inject_eol_after_tags'];
        $inject_eol_after_tags            = array_map('mb_strtolower', $inject_eol_after_tags);
        $inject_eol_after_tags_regex_frag = implode('|', c\esc_regex($inject_eol_after_tags));

        $string = preg_replace('/\<('.$strip_content_in_tags_regex_frag.')(?:\>|\s[^>]*\>).*?\<\/\\1\>/uis', '', $string);
        $string = preg_replace('/\<\/(?:'.$inject_eol_after_tags_regex_frag.')\>/ui', '${0}'."\n", $string);
        $string = preg_replace('/\<(?:'.$inject_eol_after_tags_regex_frag.')(?:\/\s*\>|\s[^\/>]*\/\s*\>)/ui', '${0}'."\n", $string);

        $string = strip_tags($string, $allowed_tags ? '<'.implode('><', $allowed_tags).'>' : '');
        $string = c\strip_html_attrs($string, compact('allowed_attributes'));
        $string = c\balance_html_tags($string); // Force balanced tags.

        $Tokenizer = c\tokenize($string, ['pre', 'code', 'samp']);
        $string    = &$Tokenizer->getString(); // By reference.

        if ($br2nl) {
            $string = preg_replace('/\<br(?:\>|\/\s*\>|\s[^\/>]*\/\s*\>)/u', "\n", $string);
            $string = c\normalize_eols($string); // Normalize line breaks.
            $string = preg_replace('/[ '."\t\x0B".']+/u', ' ', $string);
        } else {
            $string = preg_replace('/\s+/u', ' ', $string);
        }
        $string = c\normalize_html_whitespace($string);
        $string = $Tokenizer->restoreGetString();
        $string = c\html_trim($string);

        return $string;
    }
}