<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * App base abstraction.
 *
 * @since 150424 Initial release.
 */
abstract class AppBase extends Abs
{
    /**
     * App.
     *
     * @since 150424
     *
     * @type App
     */
    protected $App;

    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param App $App Instance of App.
     */
    public function __construct(App $App)
    {
        parent::__construct();

        $this->App = $App;
    }
}
