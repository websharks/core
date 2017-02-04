<?php
/**
 * Tokenizer.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Tokenizer.
 *
 * @since 150424 Initial release.
 */
class Tokenizer extends Classes\Core\Base\Core implements Interfaces\UrlConstants
{
    /**
     * ID.
     *
     * @since 150424
     *
     * @type string
     */
    protected $id;

    /**
     * String.
     *
     * @since 150424
     *
     * @type string
     */
    protected $string;

    /**
     * Tokenize what?
     *
     * @since 150424
     *
     * @type array
     */
    protected $tokenize;

    /**
     * Behavioral args.
     *
     * @since 160720
     *
     * @type array
     */
    protected $args;

    /**
     * Tokens.
     *
     * @since 150424
     *
     * @type array
     */
    protected $tokens;

    /**
     * A shortcode tag name.
     *
     * @since 160720
     *
     * @type string|null
     */
    protected $shortcode_unautop_compat_tag_name;

    /**
     * Tokenize specific elements.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App      Instance of App.
     * @param string      $string   Input string.
     * @param array       $tokenize Specific elements.
     * @param array       $args     Any behavioral args.
     *
     * @return string The tokenized string.
     */
    public function __construct(Classes\App $App, string $string, array $tokenize, array $args = [])
    {
        parent::__construct($App);

        $this->id       = $this->c::uniqueId();
        $this->string   = $string; // String to tokenize.
        $this->tokenize = $tokenize; // What to tokenize.

        $default_args = [
            'shortcode_tag_names'               => [],
            'exclude_escaped_shortcode'         => false,
            'shortcode_unautop_compat'          => false,
            'shortcode_unautop_compat_tag_name' => '',
        ]; // Establishes argument defaults.

        $this->args                                      = $args + $default_args;
        $this->args['shortcode_tag_names']               = (array) $this->args['shortcode_tag_names'];
        $this->args['exclude_escaped_shortcode']         = (bool) $this->args['exclude_escaped_shortcode'];
        $this->args['shortcode_unautop_compat']          = (bool) $this->args['shortcode_unautop_compat'];
        $this->args['shortcode_unautop_compat_tag_name'] = (string) $this->args['shortcode_unautop_compat_tag_name'];

        $this->tokens = []; // Initialize tokens.

        if (!$this->string || !$this->tokenize) {
            return; // Nothing to do.
        }
        $this->maybeTokenizeShortcodes();

        $this->maybeTokenizePreTags();
        $this->maybeTokenizeCodeTags();
        $this->maybeTokenizeSampTags();
        $this->maybeTokenizeAnchorTags();
        $this->maybeTokenizeAllTags();

        $this->maybeTokenizeMdFences();
        $this->maybeTokenizeMdLinks();
    }

    /**
     * Get tokenizer ID.
     *
     * @since 160720 Initial release.
     *
     * @return string Tokenizer ID.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get tokenized string.
     *
     * @since 150424 Initial release.
     *
     * @return string Tokenized string (by ref).
     */
    public function &getString(): string
    {
        return $this->string;
    }

    /**
     * Set tokenized string.
     *
     * @since 150424 Initial release.
     *
     * @param string Set the tokenized string.
     */
    public function setString(string $string)
    {
        $this->string = $string;
    }

    /**
     * Maybe tokenize shortcodes.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeShortcodes()
    {
        if (!in_array('shortcodes', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_strpos($this->string, '[') === false) {
            return; // No `[` shortcodes.
        } elseif (mb_strpos($this->string, ']') === false) {
            return; // No `[` shortcodes.
        }
        if ($this->args['shortcode_tag_names']) {
            $shortcode_tag_names = $this->args['shortcode_tag_names'];
        } elseif (!empty($GLOBALS['shortcode_tags']) && is_array($GLOBALS['shortcode_tags'])) {
            $shortcode_tag_names = array_keys($GLOBALS['shortcode_tags']);
        }
        if (empty($shortcode_tag_names) || !$this->c::isWordPress() || !$this->c::canCallFunc('get_shortcode_regex')) {
            return; // Not possible at this time.
        }
        if ($this->args['shortcode_unautop_compat_tag_name']) {
            $this->shortcode_unautop_compat_tag_name = $this->args['shortcode_unautop_compat_tag_name'];
        } else { // Any registered shortcode tag name; for `shortcode_unautop_compat` mode.
            $this->shortcode_unautop_compat_tag_name = $shortcode_tag_names[0];
        } // This prevents WordPress from wrapping a stand-alone token with `<p></p>`.

        $regex = '/'.get_shortcode_regex($shortcode_tag_names).'/us'; // Dot matches new line.
        // See: <https://developer.wordpress.org/reference/functions/get_shortcode_regex/>

        $this->string = preg_replace_callback($regex, function ($m) {
            // If excluding escaped shortcodes, check here before we continue.
            if ($this->args['exclude_escaped_shortcode'] && $m[1] === '[' && $m[6] === ']') {
                return $m[0]; // Exclude from tokenization.
            }
            $this->tokens[] = $m[0]; // Original data for token.
            // i.e., The entire shortcode (w/ possible escape brackets).

            if ($this->args['shortcode_unautop_compat']) {
                return $token = '['.$this->shortcode_unautop_compat_tag_name.' _is_token=⁅⒯⁆]'.
                                    '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆'.
                                '[/'.$this->shortcode_unautop_compat_tag_name.']';
            } else { // Default behavior.
                return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
            }
        }, $this->string); // Shortcodes replaced by tokens.
    }

    /**
     * Maybe tokenize `<pre>` tags.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizePreTags()
    {
        if (!in_array('pre', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_stripos($this->string, '<pre') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/\<(pre)(?:\>|\s[^>]*\>)(?:(?s:[^<>]+|(?!\<\\1[\s>]).)+?|(?R))*?\<\/\\1\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0]; // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize `<code>` tags.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeCodeTags()
    {
        if (!in_array('code', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_stripos($this->string, '<code') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/\<(code)(?:\>|\s[^>]*\>)(?:(?s:[^<>]+|(?!\<\\1[\s>]).)+?|(?R))*?\<\/\\1\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize `<samp>` tags.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeSampTags()
    {
        if (!in_array('samp', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_stripos($this->string, '<samp') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/\<(samp)(?:\>|\s[^>]*\>)(?:(?s:[^<>]+|(?!\<\\1[\s>]).)+?|(?R))*?\<\/\\1\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize `<a>` tags.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeAnchorTags()
    {
        if (!in_array('anchors', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_stripos($this->string, '<a') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/\<(a)(?:\>|\s[^>]*\>)(?:(?s:[^<>]+|(?!\<\\1[\s>]).)+?|(?R))*?\<\/\\1\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize all `<tags>`.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeAllTags()
    {
        if (!in_array('tags', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_stripos($this->string, '<') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/\<\/?[\w\-]+(?:\>|\s[^>]*\>)/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize MD fences.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeMdFences()
    {
        if (!in_array('md-fences', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_strpos($this->string, '~') === false && mb_strpos($this->string, '`') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/(~{3,}|`{3,}|`)(?s:.*?)\\1/ui';
        // This picks up ```lang {attributes} also.

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Fences replaced by tokens.
    }

    /**
     * Maybe tokenize `[markdown](links)`.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeMdLinks()
    {
        if (!in_array('md-links', $this->tokenize, true)) {
            return; // Not tokenizing these.
        }
        // This routine includes considerations for images also.
        // This also tokenizes [link][ids] that reference link definitions.
        // This also tokenizes [named]: <link> "definitions" too (supports one-line definitions only).
        // This also tokenizes *[ABBR]: definitions too (supports one-line definitions only).
        // This also tokenizes [^1]: footnote definitions too (supports one-line definitions only).
        // @TODO Add support for multiline definitions in a future revision.

        $url_re       = $this->c::regexFrag($this::URL_REGEX_VALID);
        $this->string = preg_replace_callback(
            [
                // In this first one there is a check for named link references too.
                // The negative lookahed for `(?!\/)` is to avoid catching a tokenized shortcode.
                // e.g., Whenever the `shortcode_unautop_compat` configuration option has been enabled.

                // A [link][id] can also have spaces between; e.g., [link] [id]. This is according to the spec.
                // A link or image using special attributes cannot have a space before `{attributes}`, according to the spec.
                // Any type of definition is allowed to have spaces both before and after the `:`; e.g., `[^1] : footnote`.

                '/\!?\[(?:[^\v[\]]*|(?R))\](?:[ ]*\[(?!\/)[^\v[\]]+\]|\([^\s()]+\))(?:[ ]*\{[^\v{}]*\})?/ui', // Links & images.
                '/^[ ]*(?:\[[^\v\^[\]]+\])+[ ]*\:[ ]*[^\s]+(?:[ ]+"[^\v"]+")?$/uim', // Link definitions.
                '/^[ ]*(?:\*\[[^\v[\]]+\])+[ ]*\:.*$/uim', // Abbreviation definitions.
                '/^[ ]*(?:\[\^[0-9]+\])+[ ]*\:.*$/uim', // Footnote definitions.
            ],
            function ($m) {
                $this->tokens[] = $m[0];  // Original data for token.
                return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
            },
            $this->string // Shortcodes replaced by tokens.
        );
    }

    /**
     * Restore token originals.
     *
     * @since 150424 Initial release.
     *
     * @return string After restoring tokens.
     */
    public function &restoreGetString(): string
    {
        if (!$this->tokens || mb_strpos($this->string, '⒯') === false) {
            return $this->string; // Nothing to restore in this case.
        }
        $restore_shortcode_tokens = $this->args['shortcode_unautop_compat']
            && $this->shortcode_unautop_compat_tag_name && in_array('shortcodes', $this->tokenize, true);

        foreach (array_reverse($this->tokens, true) as $_token_id => $_original) {
            // Must go in reverse order so nested tokens unfold properly.
            // If `$restore_shortcode_tokens`, first replace shortcode tokens.

            $_token = '⁅⒯'.$this->id.'⒯'.$_token_id.'⒯⁆'; // Placeholder.

            if ($restore_shortcode_tokens) { // Restoring shortcode tokens in this class instance?
                $this->string = str_replace('['.$this->shortcode_unautop_compat_tag_name.' _is_token=⁅⒯⁆]'.
                                                $_token.// Token inside the shortcode token.
                                            '[/'.$this->shortcode_unautop_compat_tag_name.']', $_original, $this->string);
            }
            $this->string = str_replace($_token, $_original, $this->string); // Always.
        } // unset($_token_id, $_token, $_original); // Housekeeping.

        return $this->string;
    }
}
