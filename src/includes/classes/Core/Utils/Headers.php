<?php
/**
 * Header utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
//
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
//
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Header utilities.
 *
 * @since 151121 Header utilities.
 */
class Headers extends Classes\Core\Base\Core implements Interfaces\HttpStatusConstants, Interfaces\SecondConstants
{
    /**
     * Current request headers.
     *
     * @since 170824.30708 Current request headers.
     *
     * @return array Unique/associative array of all headers.
     */
    public function current(): array
    {
        if (($headers = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $headers; // Cached this already.
        }
        $headers = []; // Initialize.

        foreach ($_SERVER as $_header => $_value) {
            if (mb_stripos($_header, 'HTTP_') === 0) {
                $_header                          = preg_replace('/^HTTP_/ui', '', $_header);
                $_header                          = str_replace('_', '-', $_header);
                $headers[mb_strtolower($_header)] = $_value;
            }
        } // unset($_header, $_value); // Housekeeping.

        return $headers;
    }

    /**
     * Yes-cache headers.
     *
     * @since 17xxxx Yes-cache headers.
     *
     * @param int $expires_after Expires after (seconds).
     *
     * @return array Yes-cache headers.
     */
    public function yesCache(int $expires_after = self::SECONDS_IN_YEAR): array
    {
        return [
            'pragma'        => 'public',
            'expires'       => gmdate('D, d M Y H:i:s T', time() + $expires_after),
            'cache-control' => 'public, immutable, max-age='.$expires_after,
        ];
    }

    /**
     * No-cache headers.
     *
     * @since 17xxxx No-cache headers.
     *
     * @return array No-cache headers.
     */
    public function noCache(): array
    {
        return [
            'pragma'        => 'no-cache',
            'expires'       => gmdate('D, d M Y H:i:s T', time() - $this::SECONDS_IN_YEAR),
            'cache-control' => 'no-cache, must-revalidate, max-age=0',
        ];
    }

    /**
     * Send yes-cache headers.
     *
     * @since 17xxxx Yes-cache headers.
     *
     * @param int $expires_after Expires after (seconds).
     */
    public function yesCacheSend(int $expires_after = self::SECONDS_IN_YEAR)
    {
        if (headers_sent()) {
            throw $this->c::issue('Headers already sent.');
        }
        header_remove('last-modified');

        foreach ($this->yesCache() as $_header => $_value) {
            header($_header.': '.$_value);
        } // unset($_header, $_value);
    }

    /**
     * Send no-cache headers.
     *
     * @since 17xxxx No-cache headers.
     */
    public function noCacheSend()
    {
        if (headers_sent()) {
            throw $this->c::issue('Headers already sent.');
        }
        header_remove('last-modified');

        foreach ($this->noCache() as $_header => $_value) {
            header($_header.': '.$_value);
        } // unset($_header, $_value);
    }

    /**
     * Get slug for status code.
     *
     * @since 170824.30708 Adding status slug utility.
     *
     * @param int $status The HTTP status code.
     *
     * @return string Status slug for code.
     */
    public function getStatusSlug(int $status): string
    {
        return $this->c::nameToSlug($this->getStatusTitle($status));
    }

    /**
     * Get title for status code.
     *
     * @since 17xxxx Status title utility.
     *
     * @param int $status The HTTP status code.
     *
     * @return string Status title for code.
     */
    public function getStatusTitle(int $status): string
    {
        return $this::HTTP_STATUSES[$status] ?? 'Bad Request';
    }

    /**
     * Get message for status code.
     *
     * @since 17xxxx Status message utility.
     *
     * @param int $status The HTTP status code.
     *
     * @return string Status message for code.
     */
    public function getStatusMessage(int $status): string
    {
        return $this->c::mbUcFirst(mb_strtolower($this->getStatusTitle($status))).'.';
    }

    /**
     * Status header.
     *
     * @since 151121 Header utilities.
     *
     * @param int    $status   Status code.
     * @param string $protocol Protocol.
     */
    public function sendStatus(int $status, string $protocol = '')
    {
        if (headers_sent()) {
            throw $this->c::issue('Headers already sent.');
        }
        if (!$protocol && !($protocol = $_SERVER['SERVER_PROTOCOL'] ?? '')) {
            $protocol = 'HTTP/1.1'; // Default fallback.
        }
        header($protocol.' '.$status.' '.$this->getStatusTitle($status), true, $status);
    }

    /**
     * Parses headers.
     *
     * @since 151121 Header utilities.
     *
     * @param mixed $value Input value w/ headers.
     *
     * @return array Unique/associative array of all headers.
     */
    public function parse($value): array
    {
        $headers = []; // Initialize.

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => $_value) {
                if ($_key && is_string($_key) && is_string($_value)) {
                    $headers = array_merge($headers, $this->parse($_key.': '.$_value));
                } else {
                    $headers = array_merge($headers, $this->parse($_value));
                }
            } // unset($_key, $_value);
            return $headers; // All done here.
        }
        $string = (string) $value; // Force string.

        foreach (explode("\r\n", $string) as $_rn_delimited_header) {
            if (mb_strpos($_rn_delimited_header, ':') === false) {
                continue; // Invalid header.
            }
            list($_header, $_value) = $this->c::mbTrim(explode(':', $_rn_delimited_header, 2));

            if (!$_header || !isset($_value[0])) {
                continue; // Invalid header.
            }
            $headers[mb_strtolower($_header)] = $_value;
        } // unset($_rn_delimited_header, $_header, $_value);

        return $headers;
    }
}
