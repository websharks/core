<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * URL scheme utilities.
 *
 * @since 150424 Initial release.
 */
class UrlScheme extends Classes\AbsBase implements Interfaces\UrlConstants
{
    /**
     * Set the scheme for a URL.
     *
     * @since 150424 Initial release.
     *
     * @param string $url    Absolute URL that includes a scheme (or a `//` scheme).
     * @param string $scheme ``|`//`, `current`, `default`, `relative`, `https`, `http`, or another.
     *
     * @return string $url URL w/ a specific scheme.
     */
    public function set(string $url, string $scheme = ''): string
    {
        if (!$scheme || $scheme === '//') {
            $scheme = '//'; # Inherits.
        } elseif ($scheme === 'current') {
            $scheme = c\current_scheme();
        } elseif ($scheme === 'default') {
            $scheme = $this->App->Config->urls['default_scheme'];
        }
        if (!$scheme) {
            throw new Exception('Empty scheme.');
        }
        if ($scheme === '//') {
            $url = preg_replace('/^'.$this::URL_REGEX_FRAG_SCHEME.'/u', '//', $url);
        } elseif ($scheme === 'relative') {
            $url = preg_replace('/^'.$this::URL_REGEX_FRAG_SCHEME.'[^\/]*/u', '', $url);
        } else {
            $url = preg_replace('/^'.$this::URL_REGEX_FRAG_SCHEME.'/u', $scheme.'://', $url);
        }
        return $url;
    }
}