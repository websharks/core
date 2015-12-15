<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * IP address utilities.
 *
 * @since 150424 Initial release.
 */
class Ip extends Classes\AbsBase
{
    /**
     * Get the current visitor's real IP address.
     *
     * @since 150424 Initial release.
     *
     * @return string Real IP address; else `unknown` on failure.
     *
     * @note This supports both IPv4 and IPv6 addresses.
     */
    public function current(): string
    {
        if (!is_null($ip = &$this->cacheKey(__FUNCTION__))) {
            return $ip; // Already cached this.
        }
        if (c\is_cli()) {
            throw new Exception('Not possible in CLI mode.');
        }
        $sources = array(
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_VIA',
            'REMOTE_ADDR',
        );
        foreach ($sources as $_source) {
            if (!empty($_SERVER[$_source]) && is_string($_SERVER[$_source])) {
                if (($_valid_public_ip = $this->getValidPublicFrom($_SERVER[$_source]))) {
                    return $ip = $_valid_public_ip; // IPv4 or IPv6 address.
                }
            } // unset($_valid_public_ip); // Housekeeping.
        } // unset($_source); // Housekeeping.

        if (!empty($_SERVER['REMOTE_ADDR'])) {
            return $ip = mb_strtolower((string) $_SERVER['REMOTE_ADDR']);
        }
        return $ip = 'unknown'; // Not possible.
    }

    /**
     * Geographic region code for given IP address.
     *
     * @since 150424 Initial release.
     *
     * @param string $ip An IP address to pull geographic data for.
     *
     * @return string Geographic region code for given IP address; if possible.
     */
    public function region(string $ip): string
    {
        if (($geo = $this->geoData($ip))) {
            return $geo->region;
        }
        return ''; // Empty string on failure.
    }

    /**
     * Geographic country code for given IP address.
     *
     * @since 150424 Initial release.
     *
     * @param string $ip An IP address to pull geographic data for.
     *
     * @return string Geographic country code for given IP address; if possible.
     */
    public function country(string $ip): string
    {
        if (($geo = $this->geoData($ip))) {
            return $geo->country;
        }
        return ''; // Empty string on failure.
    }

    /**
     * Geographic location data from IP address.
     *
     * @since 150424 Initial release.
     *
     * @param string $ip An IP address to query.
     *
     * @return \stdClass|bool Geo location data from IP address.
     */
    protected function geoData(string $ip)
    {
        # Valid  IP. Do we have one?

        if (!($ip = c\mb_trim(mb_strtolower($ip)))) {
            return false; // Not possible.
        }
        # Check object cache. Did we already do this?

        if (!is_null($geo = &$this->cacheKey(__FUNCTION__, $ip))) {
            return $geo; // Already cached this.
        }
        # Check filesystem cache. Did we already do this?

        if (!$this->App->Config->fs_paths['cache_dir']) {
            throw new Exception('Missing cache directory.');
        }
        $ip_sha1               = sha1($ip); // Needed below.
        $cache_dir             = $this->App->Config->fs_paths['cache_dir'].'/ip-geo-data/'.c\sha1_mod_shard_id($ip_sha1, true);
        $cache_dir_permissions = $this->App->Config->fs_permissions['transient_dirs'];
        $cache_file            = $cache_dir.'/'.$ip_sha1.'.json';

        if (is_file($cache_file) && filemtime($cache_file) >= strtotime('-30 days')) {
            $cache      = json_decode(file_get_contents($cache_file));
            return $geo = is_object($cache) ? $cache : false;
        }
        # Initialize request-related variables.

        $region = $country = ''; // Initialize.

        # Query geoPlugin service.

        $response = c\remote_request(
            'GET::http://www.geoplugin.net/json.gp?ip='.urlencode($ip),
            ['max_con_secs' => 5, 'max_stream_secs' => 5]
        );
        if (!$response || !is_object($json = json_decode($response))) {
            // Fill object cache, but do not create a cache file.
            return $geo = false; // Connection failure.
        }
        # Parse response; i.e., try to fill geo data.

        if (!empty($json->geoplugin_regionCode)) {
            $region = (string) $json->geoplugin_regionCode;
            $region = c\mb_str_pad($region, 2, '0', STR_PAD_LEFT);
            $region = mb_strtoupper($region);
        }
        if (!empty($json->geoplugin_countryCode)) {
            $country = (string) $json->geoplugin_countryCode;
            $country = mb_strtoupper($country);
        }
        # Fill object cache; i.e., `\stdClass` or `false`.

        $geo = (object) compact('region', 'country');
        if (strlen($geo->region) !== 2 || strlen($geo->country) !== 2) {
            $geo = false; // Invalid (or insufficient) data.
        }
        # Cache geo data; i.e., `\stdClass` or `false`.

        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, $cache_dir_permissions, true);
        }
        file_put_contents($cache_file, json_encode($geo));

        # Return object; or `false` on failure.

        return $geo; // `\stdClass` or `false`.
    }

    /**
     * Looks for a valid/public IP address.
     *
     * @since 150424 Initial release.
     *
     * @param string $list_of_possible_ips A single IP, or a comma-delimited list of IPs.
     *
     * @return string A valid/public IP address (if one is found); else an empty string.
     */
    protected function getValidPublicFrom(string $list_of_possible_ips): string
    {
        if (!($list_of_possible_ips = c\mb_trim($list_of_possible_ips))) {
            return ''; // Not possible; i.e., empty string.
        }
        foreach (preg_split('/[\s;,]+/u', $list_of_possible_ips, -1, PREG_SPLIT_NO_EMPTY) as $_possible_ip) {
            if (($_valid_public_ip = filter_var(mb_strtolower($_possible_ip), FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE))) {
                return $_valid_public_ip; // A valid public IPv4 or IPv6 address.
            }
        } // unset($_possible_ip, $_valid_public_ip); // Housekeeping.

        return ''; // Default return value.
    }
}