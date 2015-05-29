<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * URL scheme utilities.
 *
 * @since 150424 Initial release.
 */
class UrlSchemeUtils extends AbsBase
{
    abstract public function urlCurrentScheme();

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
     * Set the scheme for a URL.
     *
     * @since 150424 Initial release.
     *
     * @param string      $url    Absolute URL that includes a scheme (or a `//` scheme).
     * @param null|string $scheme Optional; `//`, `relative`, `https`, or `http`.
     *
     * @return string $url URL w/ a specific scheme.
     */
    public function urlSchemeSet($url, $scheme = null)
    {
        $url = (string) $url;
        if (!isset($scheme)) {
            $scheme = $this->urlCurrentScheme();
        }
        $scheme = (string) $scheme;

        if (substr($url, 0, 2) === '//') {
            $url = 'http:'.$url;
        }
        if ($scheme === '//') {
            $url = preg_replace('/^\w+\:\/\//', '//', $url);
        } elseif ($scheme === 'relative') {
            $url = preg_replace('/^\w+\:\/\/[^\/]*/', '', $url);
        } else {
            $url = preg_replace('/^\w+\:\/\//', $scheme.'://', $url);
        }
        return $url;
    }
}
