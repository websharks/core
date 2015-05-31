<?php
namespace WebSharks\Core\Classes;

/**
 * OS utilities.
 *
 * @since 150424 Initial release.
 */
class Os extends AbsBase
{
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
     * A Unix environment?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if in a Unix environment.
     */
    public function isUnix()
    {
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (stripos(PHP_OS, 'win') !== 0) {
            return ($is = true);
        }
        return ($is = false);
    }

    /**
     * A Windows environment?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if in a Windows environment.
     */
    public function isWindows()
    {
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (stripos(PHP_OS, 'win') === 0) {
            return ($is = true);
        }
        return ($is = false);
    }
}