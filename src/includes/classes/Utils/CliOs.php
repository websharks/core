<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * CLI OS-based utilities.
 *
 * @since 150424 Initial release.
 */
class CliOs extends Classes\Core
{
    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->a::isCli()) {
            throw new Exception('Requires CLI mode.');
        }
    }

    /**
     * Open a URL from the command line.
     *
     * @since 150424 Initial release.
     *
     * @param string $url The URL to open.
     */
    public function openUrl(string $url)
    {
        if (!($url = $this->a::mbTrim($url))) {
            return; // Not possible.
        }
        $url_arg = escapeshellarg($url);

        if ($this->a::isMac()) {
            `open $url_arg`;
        } elseif ($this->a::isLinux()) {
            `xdg-open $url_arg`;
        } elseif ($this->a::isWindows()) {
            `start $url_arg`;
        } else {
            throw new Exception('Unable to open <'.$url.'>. Unsupported operating system.');
        }
    }
}
