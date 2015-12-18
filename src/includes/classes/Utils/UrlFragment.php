<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * UrlFragment utilities.
 *
 * @since 150424 Initial release.
 */
class UrlFragment extends Classes\AppBase
{
    /**
     * URL without a #hash/fragment.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     *
     * @return string URL without a #hash/fragment.
     */
    public function strip(string $url_uri_qsl): string
    {
        if (mb_strpos($url_uri_qsl, '#') !== false) {
            $url_uri_qsl = mb_strstr($url_uri_qsl, '#', true);
        }
        return $url_uri_qsl;
    }
}
