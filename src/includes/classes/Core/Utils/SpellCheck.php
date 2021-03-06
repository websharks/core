<?php
/**
 * Spellcheck utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
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
 * Spellcheck utils.
 *
 * @since 150424 Adding password strength.
 */
class SpellCheck extends Classes\Core\Base\Core
{
    /**
     * Checks spelling.
     *
     * @since 150424 Adding password strength.
     *
     * @param string $word  Input word to check.
     * @param int    $flags Dictionary flags.
     *
     * @return bool True if spelled correctly.
     */
    public function __invoke(string $word, int $flags = PSPELL_NORMAL): bool
    {
        if (is_null($dictionary = &$this->cacheKey(__FUNCTION__, $flags))) {
            $dictionary = pspell_new('en', '', '', 'utf-8', $flags);
        }
        return pspell_check($dictionary, $word);
    }
}
