<?php
/**
 * Name utilities.
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
 * Name utilities.
 *
 * @since 150424 Initial release.
 */
class Name extends Classes\Core\Base\Core
{
    /**
     * Strip prefixes/suffixes.
     *
     * @since 151121 Adding name utilities.
     *
     * @param string $name Full name.
     *
     * @return string Name w/o prefixes/suffixes.
     */
    protected function stripClean(string $name)
    {
        static $last_in, $last_out;

        if ($name === $last_in && isset($last_out)) {
            return $last_out;
        } else {
            $last_in = $name;
        }
        if (!isset($name[0])) {
            return $last_out = $name;
        }
        $name = str_replace('"', '', $name);
        $name = preg_replace('/^(?:Mr\.?|Mrs\.?|Ms\.?|Dr\.?)\s+/ui', '', $name);
        $name = preg_replace('/\s+(?:Sr\.?|Jr\.?|IV|I+)$/ui', '', $name);
        $name = preg_replace('/\s+/u', ' ', $name);
        $name = $this->c::mbTrim($name);

        return $last_out = $name;
    }

    /**
     * First from full name.
     *
     * @since 151121 Adding name utilities.
     *
     * @param string $name  Full name.
     * @param string $email Fallback on email?
     *
     * @return string First name.
     */
    public function firstIn(string $name, string $email = ''): string
    {
        $name = $this->stripClean($name);

        if ($name && mb_strpos($name, ' ', 1) !== false) {
            return explode(' ', $name, 2)[0];
        } elseif (!$name && $email && mb_strpos($email, '@', 1) !== false) {
            return $this->c::mbUcFirst(explode('@', $email, 2)[0]);
        } else {
            return $name;
        }
    }

    /**
     * Last from full name.
     *
     * @since 151121 Adding name utilities.
     *
     * @param string $name Full name.
     *
     * @return string Last name.
     */
    public function lastIn(string $name): string
    {
        $name = $this->stripClean($name);

        if ($name && mb_strpos($name, ' ', 1) !== false) {
            return explode(' ', $name, 2)[1];
        } else {
            return $name;
        }
    }

    /**
     * Convert name to acronym.
     *
     * @since 160220 Initial release.
     *
     * @param string $name   Full name.
     * @param bool   $strict Strict?
     *
     * @return string Acronym; based on name.
     */
    public function toAcronym(string $name, bool $strict = true): string
    {
        $acronym = ''; // Initialize.

        $name = $this->stripClean($name);
        $name = $this->c::forceAscii($name);

        $name = preg_replace('/([a-z])([A-Z0-9])/u', '${1} ${2}', $name);
        $name = preg_replace('/([a-z])([0-9])/ui', '${1} ${2}', $name);
        $name = preg_replace('/([0-9])([a-z])/ui', '${1} ${2}', $name);
        // `s2` = `s 2`, `S3` = `S 3`, `3s` = `3 s`, `xCache` = `x Cache`.

        foreach (preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY) as $_word) {
            if (isset($_word[0]) && ctype_alnum($_word[0])) {
                $acronym .= $_word[0];
            }
        } // unset($_word);

        if ($strict && mb_strlen($acronym) < 2) {
            $acronym = $this->c::mbStrPad(mb_substr($acronym, 0, 2), 2, 'x');
        }
        return mb_strtoupper($acronym);
    }

    /**
     * Convert name to slug.
     *
     * @since 150424 Initial release.
     *
     * @param string $name   Full name.
     * @param bool   $strict Strict?
     *
     * @return string Slug; based on name.
     */
    public function toSlug(string $name, bool $strict = true): string
    {
        $name = $this->stripClean($name);

        $slug = $name; // Initialize.
        $slug = mb_strtolower($this->c::forceAscii($slug));
        $slug = preg_replace('/[^a-z0-9]+/u', '-', $slug);
        $slug = $this->c::mbTrim($slug, '', '-');

        if ($strict && $slug && !preg_match('/^[a-z]/u', $slug)) {
            $slug = 'x'.$slug; // Force `^[a-z]`.
        }
        return $slug;
    }

    /**
     * Convert name to var.
     *
     * @since 160220 Var utils.
     *
     * @param string $name   Full name.
     * @param bool   $strict Strict?
     *
     * @return string Var; based on name.
     */
    public function toVar(string $name, bool $strict = true): string
    {
        $name = $this->stripClean($name);

        $var = $name; // Initialize.
        $var = mb_strtolower($this->c::forceAscii($var));
        $var = preg_replace('/[^a-z0-9]+/u', '_', $var);
        $var = $this->c::mbTrim($var, '', '_');

        if ($strict && $var && !preg_match('/^[a-z]/u', $var)) {
            $var = 'x'.$var; // Force `^[a-z]`.
        }
        return $var;
    }
}
