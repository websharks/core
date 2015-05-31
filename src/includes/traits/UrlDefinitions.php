<?php
namespace WebSharks\Core\Traits;

/**
 * Definition properties.
 *
 * @since 150424 Initial release.
 */
trait UrlDefinitions
{
    /**
     * Regex matches a `scheme://`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex matches a `scheme://`.
     */
    protected $DEF_URL_REGEX_FRAG_SCHEME = '(?:[a-zA-Z0-9]+\:)?\/\/';

    /**
     * Regex matches a `host` name.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex matches a `host` name (TLD optional).
     */
    protected $DEF_URL_REGEX_FRAG_HOST = '[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*(?:\.[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*)*(?:\.[a-zA-Z][a-zA-Z0-9]+)?';

    /**
     * Regex matches a `host:port`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex matches a `host:port` (`:port` and TLD are optional).
     */
    protected $DEF_URL_REGEX_FRAG_HOST_PORT = '[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*(?:\.[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*)*(?:\.[a-zA-Z][a-zA-Z0-9]+)?(?:\:[0-9]+)?';

    /**
     * Regex matches a `user:pass@host:port`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex matches a `user:pass@host:port` (`user:pass@`, `:port`, and TLD are optional).
     */
    protected $DEF_URL_REGEX_FRAG_USER_HOST_PORT = '(?:[a-zA-Z0-9\-_.~+%]+(?:\:[a-zA-Z0-9\-_.~+%]+)?@)?[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*(?:\.[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*)*(?:\.[a-zA-Z][a-zA-Z0-9]+)?(?:\:[0-9]+)?';

    /**
     * Regex matches a valid URL.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex matches a valid `scheme://user:pass@host:port/path/?query#fragment` URL.
     *             Note: `scheme:`, `user:pass@`, `:port`, `TLD`, `path`, `query` and `fragment` are optional.
     */
    protected $DEF_URL_REGEX_VALID = '/^(?:[a-zA-Z0-9]+\:)?\/\/(?:[a-zA-Z0-9\-_.~+%]+(?:\:[a-zA-Z0-9\-_.~+%]+)?@)?[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*(?:\.[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*)*(?:\.[a-zA-Z][a-zA-Z0-9]+)?(?:\:[0-9]+)?(?:\/(?!\/)[a-zA-Z0-9\-_.~+%]*)*(?:\?(?:[a-zA-Z0-9\-_.~+%]+(?:\=[a-zA-Z0-9\-_.~+%&]*)?)*)?(?:#[^\s]*)?$/';
}